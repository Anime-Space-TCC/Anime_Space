<?php
// /PHP/shared/utils.php

function extrairIdGoogleDrive(string $url): ?string {
    if (preg_match('/\/file\/d\/([^\/]+)\//', $url, $matches)) {
        return $matches[1];
    }
    return null;
}
