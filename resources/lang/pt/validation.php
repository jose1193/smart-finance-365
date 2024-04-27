<?php

return [
    'required' => 'O campo :attribute é obrigatório.',
    'string' => 'O campo :attribute deve ser uma string.',
    'max' => [
        'string' => 'O campo :attribute não pode ter mais de :max caracteres.',
    ],
    'regex' => 'O formato do campo :attribute não é válido.',
    'date' => 'O campo :attribute deve ser uma data válida.',
    'exists' => 'O :attribute selecionado não é válido.',
    'unique' => 'O :attribute já está em uso.',
    'min' => [
        'string' => 'O campo :attribute deve ter pelo menos :min caracteres.',
        'password' => 'A senha deve ter pelo menos :min caracteres.',
    ],
];

