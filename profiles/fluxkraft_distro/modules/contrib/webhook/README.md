Webhook framework for Drupal

Webhook is designed to make it easy for Drupal to handle webhook callbacks in a modular and flexible way.

## Depedencies
 * CTools

## Features
Yes! This module supports features.  All webhooks are exportable.

## Extending
Webhook uses CTools plugins, which means it is easy to extend it to suit your needs.  The webhook_example project includes example plugins for handling a github callback and logging with watchdog.

### Unserializer
These plugins handle unserializing the data submitted ot the webhook.  The Webhook_Plugins_Unserializer_Interface defines the interface for creating a new unserializer.

### Processor
These plugins process the data that has been submitted.  Webhook_Plugins_Processor_Interface defines the interface for creating a processor.  The config form isn't currently used.
