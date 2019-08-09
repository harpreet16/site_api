<?php

namespace Drupal\site_api\Plugin\rest\resource;

use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Drupal\node\Entity\Node;

/**
 * Provides get response
 *
 * @RestResource(
 * 	id = "page_json_get_resource",
 *  label = @Translation("Json Page Get Resource"),
 *  uri_paths = {
 *	 "canonical" = "/page_json/{siteapikey}/{node}"
 *  }
 * )
 */
class JsonPageGetResource extends ResourceBase {

  /**
   * Constructs a Drupal\rest\Plugin\ResourceBase object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param array $serializer_formats
   *   The available serialization formats.
   * @param \Psr\Log\LoggerInterface $logger
   *   A logger instance.
   * @param \Drupal\Core\Session\AccountProxyInterface $current_user
   *   A current user instance.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    array $serializer_formats,
    LoggerInterface $logger) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $serializer_formats, $logger);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->getParameter('serializer.formats'),
      $container->get('logger.factory')->get('json_page')
    );
  }

  /**
   * Responds to GET requests.
   *
   * Returns a list of bundles for specified entity.
   *
   * @throws \Symfony\Component\HttpKernel\Exception\HttpException
   *   Throws exception expected.
   */
  public function get($siteapikey=NULL, $node=NULL) {
    //Load siteapikey
  	$site_apikey = \Drupal::config('system.site')->get('siteapikey');
    // Check for nid
  	$node_id = \Drupal::entityQuery('node')->condition('nid', $node)->execute();
		$node_exists = !empty($node_id);
    // Check if siteapikey and node with given id is present
  	if (!$site_apikey || $siteapikey != $site_apikey || $node_exists != 1) {
      throw new AccessDeniedHttpException('Access Denied');
    }
    else if($siteapikey == $site_apikey && $node_exists) {
	    $result  = Node::load($node);
	    $response = new ResourceResponse($result);
	    return $response;
  	}
  }

}
