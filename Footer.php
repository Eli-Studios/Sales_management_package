<?php

class Footer extends SalesReceiptTemplate
{
  public function output(): string { 
      foreach ($this->footerData as $data)
      {
          array_map($this->printFooter(...), $data);
          $this->tempLogContent .= PHP_EOL;
      }
      return $this->tempLogContent;
  }

  public function printFooter($value) { 
      $this->tempLogContent .= " " . str_pad(strval($value), 20 - 2);
  }

  public function __construct(private array $footerData){
      $this->tempLogContent = "";
  }
}
