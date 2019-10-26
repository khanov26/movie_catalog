<?php

namespace app\modules\admin\models;

class CreateMovieForm extends MovieForm
{
    public function rules()
    {
        return array_merge(parent::rules(), [['poster', 'required']]);
    }
}
