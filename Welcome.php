<?php
public string $startRequest;

public function getWelcome() {
	match($description) {
		"" => $returnString = $this->title . PHPEOL . PHPEOL
							 . $this-> $startRequest;
		default => $returnString = $this->title . PHPEOL. PHPEOL
								  . $this->description . PHPEOL . PHPEOL
								  . $this->startRequest;
	}
	return $returnString;
}

public function enterProgram() {
	readline();
}

public function __construct(
	public string $title,
	public string $description = ""
) {
	$this->startRequest = "Continue --> ENTER \n Quit --> Strg + C: ";
}
