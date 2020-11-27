<?php

return [
    'categories' => [
        'forms.select.optgrp.standard' => [
            'text' => [
                'trans' => 'forms.select.optgrp.options.text',
                'accept' => false,
            ],
            'number' => [
                'trans' => 'forms.select.optgrp.options.number',
                'accept' => false,
            ],
            'select' => [
                'trans' => 'forms.select.optgrp.options.select',
                'accept' => false,
            ],
            'radio' => [
                'trans' => 'forms.select.optgrp.options.radio',
                'accept' => false,
            ],
            'checkbox' => [
                'trans' => 'forms.select.optgrp.options.checkbox',
                'accept' => false,
            ],
            'hidden' => [
                'trans' => 'forms.select.optgrp.options.hidden',
                'accept' => false,
            ],
        ],
        'forms.select.optgrp.date_and_time' => [
            'date' => [
                'trans' => 'forms.select.optgrp.options.date',
                'accept' => false,
            ],
            'time' => [
                'trans' => 'forms.select.optgrp.options.time',
                'accept' => false,
            ],
            'datetime' => [
                'trans' => 'forms.select.optgrp.options.datetime',
                'accept' => false,
            ],
            'datetime-local' => [
                'trans' => 'forms.select.optgrp.options.datetime_local',
                'accept' => false,
            ],
        ],
        'forms.select.optgrp.media' => [
            'file' => [
                'trans' => 'forms.select.optgrp.options.image',
                'accept' => 'image/jpg,jpeg',
            ],
            'file' => [
                'trans' => 'forms.select.optgrp.options.picture',
                'accept' => 'image/*;capture=camera',
            ],
            'file' => [
                'trans' => 'forms.select.optgrp.options.audio',
                'accept' => 'audio/mp3',
            ],
            'file' => [
                'trans' => 'forms.select.optgrp.options.recording',
                'accept' => 'audio/*;capture=microphone',
            ],
        ],
    ]
];
