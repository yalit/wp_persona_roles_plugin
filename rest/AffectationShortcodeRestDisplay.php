<?php


class AffectationShortcodeRestDisplay
{
    public static function provideShortcodeDisplay(WP_REST_Request $request)
    {
        $body = $request->get_json_params();

        $display = static::getShortCodeDisplay($body);

        return new WP_REST_Response([
            'status' => 'success',
            'data' => $display,
        ], 200);
    }

    private static function getShortCodeDisplay(array $data)
    {
        // get the affectations
        // create displayData
        // get the display based on the format
        $mandatoryAttributes = ['format', 'filters', 'content'];
        foreach ($mandatoryAttributes as $attribute) {
            if (!array_key_exists($attribute, $data)) {
                return '';
            }
        }

        $format = $data['format'];
        $affectations = AffectationRepository::findFiltered(
            static::getFilter($data, 'parish', null),
            static::getFilter($data, 'group', null),
            static::getFilter($data, 'role', null),
            static::getAttribute($data, 'order', null)
        );
        $displayData = new DisplayData(
            static::getAttribute($data, "content", ""),
            static::getAttribute($data, "bold", ""),
            static::getAttribute($data, "underlined", ""),
            static::getAttribute($data, "italic", ""),
            
        );

        return match(FormatEnum::from($format)) {
            FormatEnum::Table => UPSTable::build($affectations, $displayData),
            FormatEnum::Card => UPSVisitCard::build($affectations, $displayData),
            default => ''
        };
    }

    private static function getAttribute($atts, $attribute, $defaultValue = null)
    {
        if (array_key_exists($attribute, $atts) && $atts[$attribute] !== "") {
            return $atts[$attribute];
        }
        return $defaultValue;
    }

    private static function getFilter($atts, $attribute, $defaultValue = null)
    {
        if (array_key_exists($attribute, $atts['filters']) && $atts['filters'][$attribute] !== "") {
            return $atts['filters'][$attribute];
        }
        return $defaultValue;
    }
}
