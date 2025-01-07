<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\Dryer;
use Illuminate\Support\Arr;

class MatchBarcode implements ValidationRule
{
    protected $recordId;

    public function __construct($recordId)
    {
        $this->recordId = $recordId;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $beforeBarcodeArray = $afterBarcodeArray =array();
        // Fetch the `before_barcodes` from the database
        $beforeBarcode = Dryer::where('id', $this->recordId)->value('before_barcodes');
        if(!empty($beforeBarcode))
        {
            $beforeBarcodeArray = array_map('trim', explode(',', $beforeBarcode));
        }


        $afterBarcodeArray = array_filter(
            array_map('trim', preg_split('/\r\n|\r|\n/', $value))
        );

        if(!empty($afterBarcodeArray))
        {
            $afterBarcodeArray          = array_unique($afterBarcodeArray);
        }


        // Check for elements in $beforeBarcodeArray that are not in $afterBarcodeArray
        $missingInAfter = array_diff($beforeBarcodeArray, $afterBarcodeArray);

        // Check for elements in $afterBarcodeArray that are not in $beforeBarcodeArray
        $extraInAfter = array_diff($afterBarcodeArray, $beforeBarcodeArray);

        if (!empty($missingInAfter))
        {
            //$fail('After dryer barcodes does not match Before the dryer barcodes.');

            $fail('Missing from before the dryer barcodes: '.implode(', ',$missingInAfter));
        }

        if(!empty($extraInAfter))
        {
            $fail('Additional items from before the dryer barcodes: '.implode(', ',$extraInAfter));
        }
       
    }
}

