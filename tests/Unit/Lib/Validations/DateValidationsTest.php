<?php

namespace Tests\Unit\Lib\Validations;

use Lib\Validations;
use Tests\TestCase;

class DateValidationsTest extends TestCase
{
    public function test_start_date_dont_bigger_end_date_should_return_true_for_valid_dates(): void
    {
        $obj = new class {
            public ?string $start_date = '2026-01-01';
            public ?string $end_date = '2026-01-31';

            /** @var array<string, string> */
            public array $errors = [];

            public function addError(string $key, string $message): void
            {
                $this->errors[$key] = $message;
            }
        };

        $result = Validations::startDateDontBiggerEndDate('start_date', 'end_date', $obj);

        $this->assertTrue($result);
        $this->assertEmpty($obj->errors);
    }

    public function test_start_date_dont_bigger_end_date_should_return_true_for_same_dates(): void
    {
        $obj = new class {
            public ?string $start_date = '2026-01-15';
            public ?string $end_date = '2026-01-15';

            /** @var array<string, string> */
            public array $errors = [];

            public function addError(string $key, string $message): void
            {
                $this->errors[$key] = $message;
            }
        };

        $this->assertTrue(Validations::startDateDontBiggerEndDate('start_date', 'end_date', $obj));
        $this->assertEmpty($obj->errors);
    }

    public function test_start_date_dont_bigger_end_date_should_return_false_when_start_is_bigger(): void
    {
        $obj = new class {
            public ?string $start_date = '2026-02-01';
            public ?string $end_date = '2026-01-01';

            /** @var array<string, string> */
            public array $errors = [];

            public function addError(string $key, string $message): void
            {
                $this->errors[$key] = $message;
            }
        };

        $result = Validations::startDateDontBiggerEndDate('start_date', 'end_date', $obj);

        $this->assertFalse($result);
        $this->assertArrayHasKey('start_date', $obj->errors);
    }

    public function test_start_date_dont_bigger_end_date_should_return_false_when_empty(): void
    {
        $obj = new class {
            public ?string $start_date = null;
            public ?string $end_date = null;

            /** @var array<string, string> */
            public array $errors = [];

            public function addError(string $key, string $message): void
            {
                $this->errors[$key] = $message;
            }
        };

        $result = Validations::startDateDontBiggerEndDate('start_date', 'end_date', $obj);

        $this->assertFalse($result);
        $this->assertArrayHasKey('start_date', $obj->errors);
    }
}
