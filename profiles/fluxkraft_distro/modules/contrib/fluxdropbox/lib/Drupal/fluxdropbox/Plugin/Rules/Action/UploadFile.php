<?php

/**
 * @file
 * Contains UploadFile.
 */

namespace Drupal\fluxdropbox\Plugin\Rules\Action;

use Drupal\fluxdropbox\Plugin\Service\DropboxAccountInterface;
use Drupal\fluxdropbox\Rules\RulesPluginHandlerBase;

/**
 * Action for posting a status message on a page.
 */
class UploadFile extends RulesPluginHandlerBase implements \RulesActionHandlerInterface {

  /**
   * Defines the action.
   */
  public static function getInfo() {
    return static::getInfoDefaults() + array(
      'name' => 'fluxdropbox_upload_file',
      'label' => t('Upload a file to dropbox'),
      'parameter' => array(
        'file_url' => array(
          'type' => 'uri',
          'label' => t('File url'),
          'description' => t('The complete url to the file. (e.g. http://www.example.com/image.jpg'),
        ),
        'filename' => array(
          'type' => 'text',
          'label' => t('Filename or filepath'),
          'description' => t('Filename or filepath (e.g. file.jpg or directory/file.jpg).'),
        ),
        'account' => static::getAccountParameterInfo(),
      ),
    );
  }

  /**
   * Executes the action.
   */
  public function execute($file_url, $filename, DropboxAccountInterface $account) {
    $client = $account->client();
    // Open a stream for reading and writing
    $stream = fopen('php://temp', 'rw');

    // Write some data to the stream
    $data = file_get_contents($file_url);
    fwrite($stream, $data);
    try {
      // Upload the file.
      $client->putStream($stream, $filename);
    }
    catch (\Dropbox\Exception\BadRequestException $e) {
      // The file extension is ignored by Dropbox (e.g. thumbs.db or .ds_store)
      rules_log('Invalid file extension', array(), \RulesLog::WARN, $this->element);
    }
    fclose($stream);
  }
}
