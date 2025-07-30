<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Student;
use App\Models\Company;

class UniqueEmailAcrossTables implements Rule
{
    protected $ignoreId;
    protected $type;

    public function __construct($ignoreId = null, $type = null)
    {
        $this->ignoreId = $ignoreId;
        $this->type = $type;
    }

    public function passes($attribute, $value)
    {
        $studentQuery = Student::where('email', $value);
        $companyQuery = Company::where('email', $value);
        $adminQuery = \App\Models\Admin::where('email', $value);

        if ($this->type === 'student') {
            $studentQuery->where('id', '!=', $this->ignoreId);
        } elseif ($this->type === 'company') {
            $companyQuery->where('id', '!=', $this->ignoreId);
        } elseif ($this->type === 'admin') {
            $adminQuery->where('id', '!=', $this->ignoreId);
        }

        return !$studentQuery->exists() && !$companyQuery->exists() && !$adminQuery->exists();
    }

    public function message()
    {
        return 'This email is already used in another account.';
    }
}
