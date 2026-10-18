<?php

namespace Database\Populate;

use App\Models\User;

class UsersPopulate
{
    public static function populate()
    {
        $data =  [
            'name' => 'Fulano',
            'email' => 'fulano@example.com',
            'password' => '123456', 
            'password_confirmation' => '123456',
            'role' => 'manager_marketing',
            'active' => true
        ];
        
        $user = new User($data);
        if (!$user->save()) {
            echo "Erro ao salvar Fulano: \n";
            print_r($user->errors ?? 'Erro desconhecido');
        }

        $data =  [
            'name' => 'Administrador',
            'email' => 'admin@teste.com',
            'password' => '123456',
            'password_confirmation' => '123456',
            'role' => 'admin',
            'active' => true
        ];
        $user = new User($data);
        $user->save();

        $data =  [
            'name' => 'Gerente de Marketing',
            'email' => 'marketing@teste.com',
            'password' => '123456',
            'password_confirmation' => '123456',
            'role' => 'manager_marketing',
            'active' => true
        ];
        $user = new User($data);
        $user->save();

        $numberOfUsers = 10;

        for ($i = 1; $i < $numberOfUsers; $i++) {
            $data =  [
                'name' => 'Fulano ' . $i,
                'email' => 'fulano ' . $i . '@example.com',
                'password' => '123456',
                'password_confirmation' => '123456',
                'role' => 'manager_marketing',
                'active' => true
            ];
            $user = new User($data);
            $user->save();
        }

        echo "Users populated with $numberOfUsers registers\n";
    }
}
