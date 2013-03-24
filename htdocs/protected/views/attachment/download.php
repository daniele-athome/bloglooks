<?php
header('Content-Type: ' . $model->mime);
header('Content-Disposition: attachment; filename="'.$model->filename.'"');
header('Content-Length: ' . $model->size);
print file_get_contents($model->path);
exit();
