<?php

/**
 * To help testability, we use an injected API instead of just a return value.
 * The injected API can be mocked to provide a mocked file_exists(), and to
 * monitor all suggested candidates, not just the correct return value.
 */
class xautoload_Mock_InjectedAPI_findFile {

  protected $testCase;
  protected $className;
  protected $expectedSuggestions;
  protected $incomingSuggestions = array();
  protected $expectedAssoc = array();
  protected $index = 0;
  protected $iAccept;
  protected $accepted = FALSE;

  function __construct($testCase, $class, $expectedSuggestions, $iAccept = -1) {
    $this->testCase = $testCase;
    $this->className = $class;
    $this->expectedSuggestions = $expectedSuggestions;
    $this->iAccept = $iAccept;
    foreach ($expectedSuggestions as $suggestion) {
      $this->expectedAssoc[$suggestion] = 0;
    }
  }

  /**
   * This is for the lazy checking of directories.
   */
  function is_dir($dir) {
    return TRUE;
  }

  function getClass() {
    return $this->className;
  }

  function suggestFile($file) {
    TRUE
      && $this->assert(isset($this->expectedAssoc[$file]),
        "'$file' is among the expected suggestions for '$this->className'.")
      && $this->assert(!$this->accepted,
        "Suggestions may not be made after one has been accepted.")
    ;
    $this->accepted = ($this->index === $this->iAccept);
    $this->incomingSuggestions[] = $file;
    ++$this->index;
    return $this->accepted;
  }

  function finish() {
    $n = count($this->expectedSuggestions);
    $this->assert($this->index === $n || $this->accepted,
      "Finish at $this->index of $n for '$this->className'.");
    if ($n <= 1) {
      return;
    }
    $expected = '';
    foreach ($this->expectedSuggestions as $i => $file) {
      if ($i === $this->iAccept) {
        $file .= ' (accept)';
      }
      $expected .= '<li>' . $file . '</li>';
    }

    $suggested = '';
    foreach ($this->incomingSuggestions as $file) {
      $suggested .= '<li>' . $file . '</li>';
    }

    $msg = <<<EOT
Suggestions must be made in the correct sequence.
<div>Expected:<ol>$expected</ol></div>
<div>Suggested:<ol>$suggested</ol></div>
EOT;
    $this->assert($this->incomingSuggestions === array_slice($this->expectedSuggestions, 0, $this->index), $msg);
  }

  protected function assert($status, $message) {
    return $this->testCase->assertPublic($status, $message);
  }
}
