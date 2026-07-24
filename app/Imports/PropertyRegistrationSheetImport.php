<?php

namespace App\Imports;

use App\Models\UserProperty;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class PropertyRegistrationSheetImport
{
    /**
     * Column keys we expect to find in the heading row. Used to locate the
     * heading row automatically, since different template variants (e.g. the
     * "no email" version) may have a different number of intro rows above it.
     */
    protected const REQUIRED_HEADINGS = [
        'acct_no', 'name_of_account', 'account_code', 'type',
        'brgy_name', 'lgu', 'date_of_registration',
    ];

    /** How many leading rows to scan when looking for the heading row. */
    protected int $maxHeadingSearchRows = 15;

    /** @var array<int, array{account_code: string, type: string}> */
    public array $duplicates = [];

    /** @var array<int, array{row: int, errors: array<int, string>}> */
    public array $invalid = [];

    public int $imported = 0;

    /**
     * Read the spreadsheet at $filePath and import each valid, non-duplicate row.
     */
    public function import(string $filePath): void
    {
        $spreadsheet = IOFactory::load($filePath);
        $rows = $spreadsheet->getActiveSheet()->toArray(null, true, true, false);

        $headingRowIndex = $this->findHeadingRowIndex($rows);
        $headings = $this->normalizeHeadings($rows[$headingRowIndex] ?? []);
        $dataRows = array_slice($rows, $headingRowIndex + 1);

        foreach ($dataRows as $index => $rawRow) {
            if ($this->isBlankRow($rawRow)) {
                continue;
            }

            $row = $this->mapRow($headings, $rawRow);
            $rowNumber = $headingRowIndex + $index + 2;

            $this->processRow($row, $rowNumber);
        }
    }

    /**
     * Scan the first few rows of the sheet and return the (0-indexed) row that
     * looks like the column heading row — i.e. the first row whose slugified
     * cells cover every required column, regardless of how many intro/title
     * rows come before it or whether an extra "Acct email address" column
     * is present.
     */
    protected function findHeadingRowIndex(array $rows): int
    {
        $limit = min($this->maxHeadingSearchRows, count($rows));

        for ($i = 0; $i < $limit; $i++) {
            $slugged = $this->normalizeHeadings($rows[$i] ?? []);

            if (count(array_intersect(self::REQUIRED_HEADINGS, $slugged)) === count(self::REQUIRED_HEADINGS)) {
                return $i;
            }
        }

        // Fall back to the historical fixed position if nothing matched
        // (e.g. an unrecognized template) so behavior degrades gracefully.
        return 3;
    }

    protected function processRow(array $row, int $rowNumber): void
    {
        $validator = Validator::make($row, $this->rules());

        if ($validator->fails()) {
            $this->invalid[] = [
                'row' => $rowNumber,
                'errors' => $validator->errors()->all(),
            ];

            return;
        }

        $exists = UserProperty::where('account_code', $row['account_code'])
            ->where('type', $row['type'])
            ->exists();

        if ($exists) {
            $this->duplicates[] = [
                'account_code' => $row['account_code'],
                'type' => $row['type'],
            ];

            return;
        }

        UserProperty::create([
            // Intentionally optional: rows without an owner email still import fine.
            // An admin links the email later via the "Manage owner email" action.
            'acct_email_address' => $this->blankToNull($row['acct_email_address'] ?? null),
            'acct_no' => $row['acct_no'],
            'name_of_account' => $row['name_of_account'],
            'account_code' => $row['account_code'],
            'type' => $row['type'],
            'lot_no' => $this->blankToNull($row['lot_no'] ?? null),
            'brgy_name' => $row['brgy_name'],
            'lgu' => $row['lgu'],
            'date_of_registration' => $this->parseDate($row['date_of_registration']),
            'status' => $this->blankToNull($row['status'] ?? null) ?? 'active',
        ]);

        $this->imported++;
    }

    /**
     * Validation rules. Note there is deliberately no rule for
     * `acct_email_address` — a blank or missing email is a valid row.
     */
    protected function rules(): array
    {
        return [
            'acct_no' => 'required|integer',
            'name_of_account' => 'required|string',
            'account_code' => 'required|string',
            'type' => 'required|in:Land,Impr/Bldg',
            'brgy_name' => 'required|string',
            'lgu' => 'required|string',
            'date_of_registration' => 'required',
        ];
    }

    /**
     * Convert the heading row into snake_case keys, e.g. "Acct Email Address" -> "acct_email_address".
     *
     * @return array<int, string>
     */
    protected function normalizeHeadings(array $headingRow): array
    {
        return array_map(
            fn ($heading) => Str::slug((string) $heading, '_'),
            $headingRow
        );
    }

    protected function isBlankRow(array $rawRow): bool
    {
        foreach ($rawRow as $value) {
            if ($value !== null && $value !== '') {
                return false;
            }
        }

        return true;
    }

    /**
     * @param  array<int, string>  $headings
     * @return array<string, mixed>
     */
    protected function mapRow(array $headings, array $rawRow): array
    {
        $row = [];

        foreach ($headings as $column => $key) {
            if ($key === '') {
                continue;
            }

            $value = $rawRow[$column] ?? null;

            // Trim stray whitespace from spreadsheet cells so that, e.g.,
            // "ABC-001 " and "ABC-001" are recognized as the same account
            // when checking for duplicates instead of being treated as two
            // different rows.
            $row[$key] = is_string($value) ? trim($value) : $value;
        }

        return $row;
    }

    protected function blankToNull(mixed $value): mixed
    {
        return $value === '' ? null : $value;
    }

    protected function parseDate(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_numeric($value)) {
            return ExcelDate::excelToDateTimeObject((float) $value)->format('Y-m-d');
        }

        try {
            return Carbon::parse((string) $value)->format('Y-m-d');
        } catch (\Throwable) {
            return null;
        }
    }
}
