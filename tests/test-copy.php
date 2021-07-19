<?php
$source = __DIR__ . '/../includes/create-business-card/uploads/background/default/default-card-front.png';
$dest = __DIR__ . '/../export/0000.png';
$copied = copy($source, $dest);
echo !$copied ? 'failed' : 'copied';
