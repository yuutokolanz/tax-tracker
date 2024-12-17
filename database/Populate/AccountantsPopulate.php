<?php

namespace Database\Populate;

use App\Models\Accountants;

class AccountantsPopulate
{
    public static function populate(): void
    {
        $data = [
            'name' => 'Teste',
            'email' => 'raul@raul.com',
            'password' => '123456',
            'password_confirmation' => '123456',
            'role_id' => 3
        ];

        $accountant = new Accountants($data);
        $accountant->save();

        $numberOfAccountants = 10;

        for ($i = 0; $i < $numberOfAccountants; $i++) {
            $id = 0;
            ($i > 5) ? $id = 1 : $id = 2;
            $data = [
                'name' => 'Teste' . $i,
                'email' => 'raul' . $i . '@raul.com',
                'password' => '123456',
                'password_confirmation' => '123456',
                'role_id' => $id
            ];

            $accountant = new Accountants($data);
            $accountant->save();
        }

        echo "Accountants populated with $numberOfAccountants register \n";
    }
}
