<?php

$path = "/";
if (isset($_GET['path'])) $path = $_GET['path'];
if (empty($path) || ($path[strlen($path)-1] == "/")) $path = substr($path, 0, strlen($path)-1);
if (empty($path) || ($path[0] != "/")) $path = "/".$path;
$relpath = ".".$path;
$isdir = (strpos($relpath, "..") === false) && is_dir($relpath);
$filelist = array();
if ($isdir) $filelist = scandir($relpath);

$current_dirs = array(array(
    "name" => "root",
    "path" => "/",
    "href" => "?path=/"
));
$path_split = array_slice((explode("/", $path)), 1);
$acc_path = "";
foreach ($path_split as $path_item) {
    if (empty($path_item)) break;
    $acc_path = $acc_path."/".$path_item;
    array_push($current_dirs, array(
        "name" => $path_item,
        "path" => $acc_path,
        "href" => "?path=".$acc_path
    ));
}

$disp_filelist = array();
if ($path != "/") {
    array_push($disp_filelist, array(
        "type" => "dir",
        "name" => "..",
        "isdir" => true,
        "path" => $current_dirs[count($current_dirs)-2]['href']
    ));
}
foreach ($filelist as $fileitem) {
    if ($fileitem == ".") continue;
    if ($fileitem == "..") continue;
    $filerelpath = $relpath."/".$fileitem;
    $filepath = $path."/".$fileitem;
    if ($path[strlen($path)-1] == "/") $filepath = substr($filepath, 1);
    $fileisdir = is_dir($filerelpath);
    $type = $fileisdir ? "dir" : "file";
    $disppath = $fileisdir ? ("?path=".$filepath) : $filerelpath;
    array_push($disp_filelist, array(
        "type" => $type,
        "name" => $fileitem,
        "isdir" => $fileisdir,
        "path" => $disppath
    ));
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $path ?></title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<?php if (!$isdir) { ?>
    <div class="message-box">不合法的位置</div>
<?php } else { ?>
    <div class="path-bar">
        <?php foreach ($current_dirs as $item) { ?>
            <a href="<?php echo $item['href']; ?>">
                <?php echo $item['name']; ?>
            </a>
            <span class="spliter">/</span>
        <?php } ?>
    </div>
    <div class="file-list">
    <?php foreach ($disp_filelist as $item) { ?>
        <div class="item">
            <a href="<?php echo $item['path']; ?>" <?php if (!$item['isdir']) { echo 'target="_blank"'; } ?> class="icon icon-<?php echo $item['type']; ?>"></a>
            <p class="text">
                <a href="<?php echo $item['path']; ?>" <?php if (!$item['isdir']) { echo 'target="_blank"'; } ?> >
                    <?php echo $item['name'] ?>
                </a>
            </p>
        </div>
    <?php } ?>
    </div>
<?php } ?>
</body>
</html>