function GetTemplatePath($lang,$type)
{
    return "./posts/{0}/{1}s/{2}" -f $lang,$type,"_template.jade"
}

function GetPublicPath($lang,$type,$area)
{
    return "./posts/{0}/{1}s/{2}" -f $lang,$type,$area
}

function GetOutputFilePath($lang,$type,$area,$id)
{
    return "./posts/{0}/{1}s/{2}/{3}.jade" -f $lang,$type,$area,$id
}

function GetOutputImagePath($type,$area,$id)
{
    return "./assets/img/{0}/{1}/{2}" -f $type,$area,$id
}

function GetPublicImagePath($type,$area,$id)
{
    return "img/{0}/{1}/{2}" -f $type,$area,$id
}

function Generate-Thumbnail($image_files,$output_folder_image_path)
{
    $resized_image_files = @()
    foreach($image_file in $image_files)
    {
        if($image_file -match "(png|jpg|gif)$")
        {
            $output_image_file_path = "{0}/{1}" -f $output_folder_image_path,$image_file
            &"C:\Program Files\GraphicsMagick-1.3.25-Q16\gm.exe" convert -auto-orient -strip $image_file.FullName $output_image_file_path
            $resized_image_file_name = "thumb_{0}" -f $image_file
            $resized_image_file_path = "{0}/thumb_{1}" -f $output_folder_image_path,$image_file
            &"C:\Program Files\GraphicsMagick-1.3.25-Q16\gm.exe" convert -auto-orient -strip -resize 256x256 $image_file.FullName $resized_image_file_path
            $resized_image_files += @{thumb = $resized_image_file_name; original = $image_file}
        }
    }
    return $resized_image_files
}

function Generate-Post($type,$area,$id,$lang)
{
    $template_path = GetTemplatePath $lang $type
    $output_folder_path = GetPublicPath $lang $type $area
    $output_file_path = GetOutputFilePath $lang $type $area $id

    $output_folder_image_path = GetOutputImagePath $type $area $id
    $public_folder_image_path = GetPublicImagePath $type $area $id

    $eixst = Test-Path $output_folder_image_path
    if($eixst -eq $false)
    {
        New-Item $output_folder_image_path -ItemType directory
    }

    $exist = Test-Path $output_folder_path 
    if($exist -eq $false)
    {
        New-Item $output_folder_path -ItemType directory
    }

    $post = Get-Content -Path $template_path -Encoding UTF8

    $temp = "<div id=`thumbnail`">"
    $image_files = Get-ChildItem -Path $import_image_path
    $resized_image_files = Generate-Thumbnail $image_files $output_folder_image_path
    foreach($resize_image_file in $resized_image_files)
    {
        $temp += "`t<a href=`"/{0}/{1}`" data-lightbox=`"room-images`">" -f $public_folder_image_path,$resize_image_file.original
        $temp += "`t`t<img src=`"/{0}/{1}`" />" -f $public_folder_image_path,$resize_image_file.thumb
    }
    $temp += "`t<!--- image here --->"
    $temp += "</div>"

    $post = $post.Replace("<!--- image here --->",$temp)

    $Utf8NoBomEncoding = New-Object System.Text.UTF8Encoding $False
    [System.IO.File]::WriteAllLines($output_file_path, $post, $Utf8NoBomEncoding)

    Write-Host ("{0} is written" -f $output_file_path)
}

function ImportImageToPost($type,$area,$id,$lang)
{
    $output_folder_path = GetPublicPath $lang $type $area
    $output_file_path = GetOutputFilePath $lang $type $area $id

    $output_folder_image_path = GetOutputImagePath $type $area $id
    $public_folder_image_path = GetPublicImagePath $type $area $id

    $eixst = Test-Path $output_folder_image_path
    if($eixst -eq $false)
    {
        New-Item $output_folder_image_path -ItemType directory
    }

    $exist = Test-Path $output_folder_path 
    if($exist -eq $false)
    {
        New-Item $output_folder_path -ItemType directory
    }

    $post = Get-Content -Path $output_file_path -Encoding UTF8

    $temp = "<div id=`thumbnail`">"
    $image_files = Get-ChildItem -Path $import_image_path
    $resized_image_files = Generate-Thumbnail $image_files $output_folder_image_path
    foreach($resize_image_file in $resized_image_files)
    {
        $temp += "`t<a href=`"/{0}/{1}`" data-lightbox=`"room-images`">" -f $public_folder_image_path,$resize_image_file.original
        $temp += "`t`t<img src=`"/{0}/{1}`" />" -f $public_folder_image_path,$resize_image_file.thumb
    }
    $temp += "`t<!--- image here --->"
    $temp += "</div>"

    $post = $post.Replace("<!--- image here --->",$temp)
    $Utf8NoBomEncoding = New-Object System.Text.UTF8Encoding $False
    [System.IO.File]::WriteAllLines($output_file_path, $post, $Utf8NoBomEncoding)

    Write-Host ("{0} is written" -f $output_file_path)
}

Add-Type -AssemblyName Microsoft.VisualBasic

function Remove-Item-ToRecycleBin($Path) {
    $item = Get-Item -Path $Path -ErrorAction SilentlyContinue
    if ($item -eq $null)
    {
        Write-Error("'{0}' not found" -f $Path)
    }
    else
    {
        $fullpath=$item.FullName
        Write-Verbose ("Moving '{0}' to the Recycle Bin" -f $fullpath)
        if (Test-Path -Path $fullpath -PathType Container)
        {
            [Microsoft.VisualBasic.FileIO.FileSystem]::DeleteDirectory($fullpath,'OnlyErrorDialogs','SendToRecycleBin')
        }
        else
        {
            [Microsoft.VisualBasic.FileIO.FileSystem]::DeleteFile($fullpath,'OnlyErrorDialogs','SendToRecycleBin')
        }
    }
}
function RemovePost($type,$area,$id,$lang)
{
    $output_file_path = GetOutputFilePath $lang $type $area $id

    $output_folder_image_path = GetOutputImagePath $type $area $id

    $exist = Test-Path $output_file_path
    if($exist -eq $true)
    {
        Remove-Item-ToRecycleBin $output_file_path
    }

    $exist = Test-Path $output_folder_image_path
    if($exist -eq $true)
    {
        Remove-Item-ToRecycleBin $output_folder_image_path
    }

    Write-Host ("{0} is removed to recycle bin" -f $output_file_path)
    Write-Host ("{0} is removed to recycle bin" -f $output_folder_image_path)
}

$command = Read-Host "command(add/import/remove/list)"
if($command -eq "list")
{
    Get-ChildItem .\posts -Recurse -Filter *.jade -Name
}else{
    $type = Read-Host "type"
    $area = read-host "area"
    $id = read-host "id"

    if($command -eq "add")
    {
        $import_image_path = read-host "import image path"
        foreach($lang in "en","ja")
        {
            Generate-Post $type $area $id $lang
        }
    }
    if($command -eq "import")
    {
        $import_image_path = read-host "import image path"
        foreach($lang in "en","ja")
        {
            ImportImageToPost $type $area $id $lang
        }
    }
    if($command -eq "remove")
    {
        foreach($lang in "en","ja")
        {
            RemovePost $type $area $id $lang
        }
    }
}
Read-Host "hit any key"
