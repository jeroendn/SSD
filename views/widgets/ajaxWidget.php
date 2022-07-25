<?php
require_once __DIR__ . '/../../php/session.php';

$integration = $_GET['integration'] ?? null;
$widget = $_GET['widget'] ?? null;

if (empty($integration) || empty($widget)) {
  echo json_encode(['error' => true, 'message' => 'Missing required parameters']);
  exit;
}

getWidget($integration, $widget);
exit;