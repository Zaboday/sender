<?php

namespace Sender\Converters;

use Sender\Exceptions\ServerResponseException;

/**
 * Class XmlConverter. Convert request data to XML
 *
 * @package Sender\Formats
 */
class XmlConverter implements ConverterInterface
{
    /**
     * Get XML string by data
     *
     * @param string $startKey
     * @param array $data - must contain 1 start index
     *
     * @return string
     * @throws ServerResponseException
     */
    public function convertTo(string $startKey, array $data): string
    {
        try {
            $startTag = $startKey;
            $xml_data = new \SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><' . $startTag . '></' . $startTag . '>');
            $this->arrayToXml($data, $xml_data);
            $result = $xml_data->asXML();
            if ($result === false) {
                throw new \InvalidArgumentException('Empty result XML document.');
            }
        } catch (\Exception $e) {
            throw new ServerResponseException('Conversion to XML is failed. ' . $e->getMessage());
        }

        return $result;
    }

    /**
     * Converts XML response to KV array
     *
     * @param string $string
     *
     * @return mixed
     * @throws ServerResponseException
     */
    public function convertFrom($string): array
    {
        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($string);
        if ($xml === false) {
            throw new ServerResponseException('Can\'t convert from XML to data. Invalid XML format.');
        }

        return (array)$xml;
    }

    /**
     * Helper for convert to xml
     *
     * @param $data
     * @param \SimpleXMLElement $xml_data
     */
    private function arrayToXml($data, &$xml_data): void
    {
        foreach ($data as $key => $value) {
            if (is_numeric($key)) {
                $key = 'item' . $key;
            }
            if (is_array($value)) {
                $subNode = $xml_data->addChild($key);
                $this->arrayToXml($value, $subNode);
            } else {
                $xml_data->addChild($key, htmlspecialchars($value));
            }
        }
    }
}