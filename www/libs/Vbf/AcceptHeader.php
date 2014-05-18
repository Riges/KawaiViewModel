<?php

/**
 * Parse all the elements of an "Accept:" header into type/subType/q elements.
 */
function parse_accept_header($header)
{
    $medias = explode(',', $header);
    $result = array();
    //$i = 0;
    foreach ($medias as $media) {
        $infos = array('q' => 1);

        $mediaParts = explode(';', $media);
        if (count($mediaParts) == 0) continue;

        $mediaNameParts = explode('/', $mediaParts[0]);
        if (count($mediaNameParts) != 2) continue;
        $infos['type'] = trim($mediaNameParts[0]);
        $infos['subType'] = trim($mediaNameParts[1]);
        if (($infos['type'] == '*') && ($infos['subType'] != '*')) continue;

        $params = array_slice($mediaParts, 1);
        if (count($params) > 0) {
            foreach ($params as $param) {
                $paramParts = explode('=', $param);
                if (count($paramParts) != 2) continue;
                if (trim($paramParts[0]) != 'q') continue;
                $infos['q'] = (float)trim($paramParts[1]);
                $infos['q'] = min($infos['q'], 1);
                $infos['q'] = max($infos['q'], 0);
            }
        }

        $result[] = $infos;
    }
    if (count($result) == 0) {
        $result[] = array('q' => 1, 'type' => '*', 'subType' => '*');
    }
    return $result;
}

/**
 * Assign a quality for one content type (between 0 and 1) or -1 if it isn't supported.
 */
function get_accept_quality($contentType, $acceptHeader)
{
    //$quality = -1;
    /*foreach ($acceptHeader as $media) {

    }*/
}