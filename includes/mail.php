<?php

if ((!defined('CMS_SYSTEM')) AND (!defined('CMS_ADMIN'))) die('Stop!!!');

require_once("global.php");

class Mail {
	var $subject;
	var $recipient;
	var $body;
	var $from;
	var $boundary;
	var $attachment;
	var $method;
	var $username;
	var $password;
	var $cc;
	var $bcc;
	var $SMTPHost;
	var $SMTPPort;
	var $extraHeader;
	var $type;
	var $plainBody;

	function Mail() {
		$numArgs = func_num_args();
		$this->boundary = uniqid(rand(), true);
		$this->attachment = array();
		$this->recipient = array();
		$this->cc = array();
		$this->bcc = array();
		$this->method = "PHP";
		$this->SMTPPort = 25;
		$this->extraHeader = "";
		$this->plainBody = "";
		if ($numArgs == 0) {
			$this->from = "";
			$this->body = "";
			$this->subject = "";
		}
		elseif ($numArgs >= 4) {
			$args = func_get_args();
			$this->setFrom($args[0]);
			if (is_array($args[1])) {
				foreach ($args[1] as $recipient) {
					$this->addRecipient($recipient);
				}
			} else {
				$this->addRecipient($args[1]);
			}
			$this->setSubject($args[2]);
			$this->setBody($args[3]);
			if ($numArgs >= 5) {
				$this->setMethod($args[4]);
				if ($numArgs == 6) { $this->setExtraHeader($args[5]); }
				if ($numArgs >= 8) {
					$this->setSMTPHost($args[5]);
					$this->setUsername($args[6]);
					$this->setPassword($args[7]);
					if ($numArgs >= 9) {
						$this->setSMTPPort($args[8]);
						if ($numArgs == 10) {
							$this->setExtraHeader($args[9]);
						}
					}
				}
			}
		}
	}

	function setPlainBody($body) {
		$this->plainBody = $body;
	}

	function setExtraHeader($extraHeader) {
		if (substr($extraHeader, -2) != "\r\n") $extraHeader .= "\r\n";
		$this->extraHeader = $extraHeader;
	}

	function setSMTPHost($host) {
		$this->SMTPHost = $host;
	}

	function setSMTPPort($port) {
		$this->SMTPPort = intval($port);
	}

	function setMethod($method) {
		$this->method = $method;
	}

	function setUsername($username) {
		$this->username = $username;
	}

	function setPassword($password) {
		$this->password = $password;
	}

	function setSubject($subject) {
		$this->subject = $subject;
	}

	function addRecipient($recipient) {
		if ($this->checkAddressValidity($recipient)) $this->recipient[] = $recipient;
	}

	function addCC($cc) {
		if ($this->checkAddressValidity($cc)) $this->cc[] = $cc;
	}

	function addBCC($bcc) {
		if ($this->checkAddressValidity($bcc)) $this->bcc[] = $bcc;
	}

	function setBody($body) {
		$searchFor = array("\r\n", "\n");
		$replaceWith = array("<br>", "<br>");
		$body = str_replace($searchFor, $replaceWith, $body);
		$this->body = $body;
	}

	function setFrom($from) {
		$this->from = $from;
	}

	function attach($file, $newname = '', $mimetype = '') {
		$content = file_get_contents($file);
		if (!$content)
		{
			Common::debug("Error attaching files.");
			return false;
		}
		$content = chunk_split(base64_encode($content));
		if (empty($mimetype)) $mime = mime_content_type($file);
		else $mime = $mimetype;
		if (!empty($newname)) $file = $newname;
		$this->attachment[] = array("content" => $content, "filename" => $file, "mime" => $mime);
		return true;
	}

	function checkAddressValidity($address) {
		if (preg_match('/[^ ]+\@[^ ]+/', $address)) return true;
		else return false;
	}

	function send() {
		list($header, $msg) = $this->prepareMail();
		if ($this->method == "PHP") $ret = $this->sendPHP($header, $msg);
		elseif ($this->method == "SMTP") $ret = $this->sendSMTP($header, $msg);
		return $ret;
	}

	function prepareMail() {
		if (count($this->recipient) < 1) {
			Common::debug("No recipient");
		}
		$to = implode(",", $this->recipient);
		$cc = "";
		if (count($this->cc) > 0) {
			$cc = implode(",", $this->cc);
			$cc = "Cc: $cc\r\n";
		}
		$bcc = "";
		if (count($this->bcc) > 0) {
			$bcc = implode(",", $this->bcc);
			$bcc = "Bcc: $bcc\r\n";
		}
		$header = "To: $to\r\n"
		."From: {$this->from}\r\n";
		if (!empty($cc)) $header .= $cc;
		if (!empty($bcc)) $header .= $bcc;
		$header .= "Subject: {$this->subject}\r\n"
		."MIME-Version: 1.0\r\n"
		."Content-Type: multipart/mixed; boundary=\"{$this->boundary}\"\r\n"
		."Content-Transfer-Encoding: 7bit\r\n";
		if (!empty($this->extraHeader)) $header .= $this->extraHeader;
		$msg = '';
		if (!empty($this->plainBody))
		{
			$msg .= "--{$this->boundary}\r\n"
				."Content-Type: text/plain; charset=UTF-8\r\n"
				."Content-Transfer-Encoding: 7bit\r\n\r\n"
				."{$this->plainBody}\r\n\r\n";
		}
		
		$msg .= "--{$this->boundary}\r\n"
			."Content-Type: text/html; charset=UTF-8\r\n"
			."Content-Transfer-Encoding: 7bit\r\n\r\n"
			."<html xmlns=\"http://www.w3.org/1999/xhtml\"><body>{$this->body}</body></html>\r\n\r\n";

		foreach ($this->attachment as $att)
		{
			$msg .= "--{$this->boundary}\r\n"
			."Content-Type: {$att["mime"]}\r\n"
			."Content-Transfer-Encoding: base64\r\n"
			."Content-Disposition: attachment; filename=\"{$att["filename"]}\"\r\n\r\n{$att["content"]}";
		}

		$msg  .= "\r\n--{$this->boundary}--";

		return array($header, $msg);
	}

	function SMTPParse($socket, $response) {
		$server_response = '';
		while (substr($server_response, 3, 1) != ' ') {
			if(!($server_response = fgets($socket, 256))) {
				Common::debug("Couldn't get mail server response code");
			}
		}

		if (!(substr($server_response, 0, 3) == $response)) {
			Common::debug("Ran into problems sending mail. Response: $server_response");
		}
	}

	function sendSMTP($header, $msg) {
		if (!$socket = fsockopen($this->SMTPHost, $this->SMTPPort, $errno, $errstr, 20)) {
			Common::debug("Could not connect to SMTP host : $errno : $errstr");
		}
		$this->SMTPParse($socket, "220");

		if (!empty($this->username) && !empty($this->password)) {
			fputs($socket, "EHLO " . $this->SMTPHost . "\r\n");
			$this->SMTPParse($socket, "250");
			fputs($socket, "AUTH LOGIN\r\n");
			$this->SMTPParse($socket, "334");
			fputs($socket, base64_encode($this->username) . "\r\n");
			$this->SMTPParse($socket, "334");
			fputs($socket, base64_encode($this->password) . "\r\n");
			$this->SMTPParse($socket, "235");
		}
		else {
			fputs($socket, "HELO " . $this->SMTPHost . "\r\n");
			$this->SMTPParse($socket, "250");
		}

		fputs($socket, "MAIL FROM: " . $this->from . "\r\n");
		$this->SMTPParse($socket, "250");

		foreach ($this->recipient as $rcp) {
			fputs($socket, "RCPT TO: $rcp\r\n");
			$this->SMTPParse($socket, "250");
		}

		foreach ($this->cc as $cc) {
			fputs($socket, "RCPT TO: $cc\r\n");
			$this->SMTPParse($socket, "250");
		}

		foreach ($this->bcc as $bcc) {
			fputs($socket, "RCPT TO: $bcc\r\n");
			$this->SMTPParse($socket, "250");
		}

		fputs($socket, "DATA\r\n");
		$this->SMTPParse($socket, "354");

		fputs($socket, "$header\r\n");
		fputs($socket, "$msg\r\n");
		fputs($socket, ".\r\n");
		$this->SMTPParse($socket, "250");
		fputs($socket, "QUIT\r\n");
		fclose($socket);

		return true;
	}

	function sendPHP($header, $msg) {
		$to = implode(",", $this->recipient);
		return mail($to, $this->subject, $msg, $header);
	}
}
?>