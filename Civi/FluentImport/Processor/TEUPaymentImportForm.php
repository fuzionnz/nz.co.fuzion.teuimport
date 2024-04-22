<?php
namespace Civi\FluentImport\Processor;

use Civi\FluentImport;

class TEUPaymentImportForm extends Form {

  public function getInputs(): array {
    return [];
  }

  public function runFormInput(FluentImport $f): FluentImport {
    return $f;
  }

}
