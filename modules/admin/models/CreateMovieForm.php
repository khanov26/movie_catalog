<?php

namespace app\modules\admin\models;

class CreateMovieForm extends MovieForm
{
    public function rules()
    {
        return parent::rules() + ['poster', 'required'];
    }
}
