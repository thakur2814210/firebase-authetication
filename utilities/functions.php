<?php

function hash_plain_password($password)
{
  /**
   * In this case, we want to increase the default cost for BCRYPT to 12.
   * Note that we also switched to BCRYPT, which will always be 60 characters.
   */
  $options = [
    'cost' => 12,
  ];
  return password_hash($password, PASSWORD_BCRYPT, $options);
}

function check_password_hash($password, $hash)
{
  /**
   * Not really that useful but to keep code consistency or to do aditional checks
   **/

  return password_verify($password, $hash);
}

function safe_json_encode($value, $options = 0, $depth = 512, $utfErrorFlag = false)
{
  $encoded = json_encode($value, $options, $depth);
  switch (json_last_error()) {
    case JSON_ERROR_NONE:
      return $encoded;
    case JSON_ERROR_DEPTH:
      return 'Maximum stack depth exceeded'; // or trigger_error() or throw new Exception()
    case JSON_ERROR_STATE_MISMATCH:
      return 'Underflow or the modes mismatch'; // or trigger_error() or throw new Exception()
    case JSON_ERROR_CTRL_CHAR:
      return 'Unexpected control character found';
    case JSON_ERROR_SYNTAX:
      return 'Syntax error, malformed JSON'; // or trigger_error() or throw new Exception()
    case JSON_ERROR_UTF8:
      $clean = utf8ize($value);
      if ($utfErrorFlag) {
        return 'UTF8 encoding error'; // or trigger_error() or throw new Exception()
      }
      return safe_json_encode($clean, $options, $depth, true);
    default:
      return 'Unknown error'; // or trigger_error() or throw new Exception()

  }
}

function utf8ize($item)
{
  if (is_array($item))
    foreach ($item as $k => $v)
      $item[$k] = utf8ize($v);

  else if (is_object($item))
    foreach ($item as $k => $v)
      $item->$k = utf8ize($v);

  else
    return utf8_encode($item);

  return $item;
}
