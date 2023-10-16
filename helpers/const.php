<?php

const CONTEST_STATUS_DRAFT = 'draft';
const CONTEST_STATUS_PENDING_APPROVED = 'pending_approved';
const CONTEST_STATUS_PUBLISH = 'publish';

const TYPE_USER_SUPER_ADMIN = 'super-admin';
const TYPE_USER_ADMIN = 'admin';
const TYPE_USER_STUDENT = 'student';

const EXCEL_QUESTION = [
    'KEY_COLUMNS' => [
        'TITLE_ORIGIN' => 0,
        'TITLE_EXTRA' => 1,
        'EXPLAIN' => 2,
        'ANSWER' => 3,
        'IS_CORRECT' => 4,
        'ORDER' => 5,
        'IMAGE_CODE_QUESTION_SHEET' => 6,
        'IMAGE_CODE_IMAGE_SHEET' => 0,
        'IMAGE' => 1,
    ],
];

const EXAMPLE_QUESTIONS_IMPORT_FILE = 'ex_files/example_questions_import.xlsx';
