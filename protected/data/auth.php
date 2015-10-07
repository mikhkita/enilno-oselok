<?php
return array (
  'rootActions' => 
  array (
    'type' => 0,
    'description' => 'Доступ только root',
    'bizRule' => NULL,
    'data' => NULL,
  ),
  'managerActions' => 
  array (
    'type' => 0,
    'description' => 'Доступ manager и root',
    'bizRule' => NULL,
    'data' => NULL,
  ),
  'manager' => 
  array (
    'type' => 2,
    'description' => '',
    'bizRule' => NULL,
    'data' => NULL,
    'children' => 
    array (
      0 => 'managerActions',
    ),
    'assignments' => 
    array (
      7 => 
      array (
        'bizRule' => NULL,
        'data' => NULL,
      ),
      8 => 
      array (
        'bizRule' => NULL,
        'data' => NULL,
      ),
      10 => 
      array (
        'bizRule' => NULL,
        'data' => NULL,
      ),
      12 => 
      array (
        'bizRule' => NULL,
        'data' => NULL,
      ),
    ),
  ),
  'root' => 
  array (
    'type' => 2,
    'description' => '',
    'bizRule' => NULL,
    'data' => NULL,
    'children' => 
    array (
      0 => 'manager',
      1 => 'rootActions',
    ),
    'assignments' => 
    array (
      1 => 
      array (
        'bizRule' => NULL,
        'data' => NULL,
      ),
    ),
  ),
);
