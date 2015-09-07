<?php
/**
 * For every node that starts with $startNode and ends with $endNode call $callback
 * with the string as an argument
 *
 * Note: Sometimes it returns two nodes instead of a single one, this could easily be
 * handled by the callback though. This function primary job is to split a large file
 * into manageable XML nodes.
 *
 * the callback will receive one parameter, the XML node(s) as a string
 *
 * @param resource $handle - a file handle
 * @param string $startNode - what is the start node name e.g <item>
 * @param string $endNode - what is the end node name e.g </item>
 * @param callable $callback - an anonymous function
 */
function nodeStringFromXMLFile($handle, $startNode, $endNode, $callback=null) {
    $cursorPos = 0;
    $i = 0;
    
    while($i < 10) {      
        // Find start position
        $startPos = getPos($handle, $startNode, $cursorPos);
        // We reached the end of the file or an error
        if($startPos === false) { 
            break;
        }
        // Find where the node ends
        $endPos = getPos($handle, $endNode, $startPos) + mb_strlen($endNode);
        // Jump back to the start position
        fseek($handle, $startPos);
        // Read the data
        $data[] = fread($handle, ($endPos-$startPos));
        // pass the $data into the callback
        //$callback($data);
                
        // next iteration starts reading from here
        $cursorPos = ftell($handle);
        
        $i++;
    }   
    
    return $data;
}

/**
 * This function will return the first string it could find in a resource that matches the $string.
 *
 * By using a $startFrom it recurses and seeks $chunk bytes at a time to avoid reading the 
 * whole file at once.
 * 
 * @param resource $handle - typically a file handle
 * @param string $string - what string to search for
 * @param int $startFrom - strpos to start searching from
 * @param int $chunk - chunk to read before rereading again
 * @return int|bool - Will return false if there are EOL or errors
 */
function getPos($handle, $string, $startFrom=0, $chunk=1024, $prev='') {
    // Set the file cursor on the startFrom position
    fseek($handle, $startFrom, SEEK_SET);
    // Read data
    $data = fread($handle, $chunk);
    // Try to find the search $string in this chunk
    $stringPos = mb_strpos($prev.$data, $string);
    // We found the string, return the position
    if($stringPos !== false ) {
        return $stringPos+$startFrom - mb_strlen($prev);
    }
    // We reached the end of the file
    if(feof($handle)) {
        return false;
    }
    // Recurse to read more data until we find the search $string it or run out of disk
    return getPos($handle, $string, $chunk+$startFrom, $chunk, $data);
}

/**
 * Turn a string version of XML and turn it into an array by using the 
 * SimpleXML
 *
 * @param string $nodeAsString - a string representation of a XML node
 * @return array
 */
function getArrayFromXMLString($nodeAsString) {
    $simpleXML = simplexml_load_string($nodeAsString);
    if(libxml_get_errors()) {
        user_error('Libxml throws some errors.', implode(',', libxml_get_errors()));
    }
    //return array_slice(simplexml2array($simpleXML), 0, 10);
    return simplexml2array($simpleXML);
}

/**
 * Turns a SimpleXMLElement into an array
 *
 * @param SimpleXMLelem $xml
 * @return array 
 */
function simplexml2array($xml) {
    if(is_object($xml) && get_class($xml) == 'SimpleXMLElement') {
        $attributes = $xml->attributes();
        foreach($attributes as $k=>$v) {
            $a[$k] = (string) $v;
        }
        $x = $xml;
        $xml = get_object_vars($xml);
    }

    if(is_array($xml)) {
        if(count($xml) == 0) { 
            return (string) $x; 
        }
        $r = array();
        foreach($xml as $key=>$value) {
            $r[$key] = simplexml2array($value);
        }
        // Ignore attributes
        if (isset($a)) {
            $r['@attributes'] = $a;
        }
        return $r;
    }
    return (string) $xml;
}
