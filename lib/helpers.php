<?php

use App\Models\Shop\Tax;
use League\ISO3166\ISO3166;
use Illuminate\Support\Optional;

if (!function_exists('member_url')) {
    function member_url($path = '')
    {
        return config('coderstm.member_url') . '/' . $path;
    }
}

if (!function_exists('trans_status')) {
    function trans_status($action = null, $module = null, $attribute = null)
    {
        return trans('messages.module.' . $action, [
            'module' => trans_choice('modules.' . $module, 1),
            'type' => trans('messages.attributes.' . $attribute)
        ]);
    }
}

if (!function_exists('trans_module')) {
    function trans_module($action = null, $module = null, $count = 1)
    {
        return trans('messages.module.' . $action, ['module' => trans_choice('modules.' . $module, $count)]);
    }
}

if (!function_exists('trans_modules')) {
    function trans_modules($action = null, $module = null)
    {
        return trans_module($action, $module, 2);
    }
}

if (!function_exists('trans_attribute')) {
    function trans_attribute($key = null, $type = null)
    {
        return trans('messages.' . $key, ['type' => trans('messages.attributes.' . $type)]);
    }
}


if (!function_exists('has')) {
    /**
     * Provide access to optional objects.
     *
     * @param  mixed  $value
     * @param  callable|null  $callback
     * @return mixed
     */
    function has($value = null, callable $callback = null)
    {
        $value = is_object($value) ? $value : (object) $value;

        if (is_null($callback)) {
            return new Optional($value);
        } elseif (!is_null($value)) {
            return $callback($value);
        }
    }

    if (!function_exists('get_country_code')) {
        function get_country_code(string $country = '')
        {
            try {
                $country = (new ISO3166)->name($country);
                return $country['alpha2'];
            } catch (\Exception $e) {
                return '*';
            }
        }
    }

    if (!function_exists('country_taxes')) {
        function country_taxes($countryCode = null, $state = null)
        {
            $taxes = Tax::where('code', get_country_code($countryCode))
                ->orderBy('priority');

            if ($state) {
                $taxes->whereIn('state', ['*', $state]);
            } else {
                $taxes->whereIn('state', ['*']);
            }

            return $taxes->get()
                ->map(function ($item) {
                    return array_merge($item->only(['label', 'rate']), [
                        'type' => $item->compounded ? 'compounded' : 'normal'
                    ]);
                })->toArray();
        }
    }

    if (!function_exists('default_tax')) {
        function default_tax()
        {
            $taxes = country_taxes(config('app.country'));
            if (count($taxes) > 0) {
                return $taxes;
            }
            return rest_of_world_tax();
        }
    }

    if (!function_exists('rest_of_world_tax')) {
        function rest_of_world_tax()
        {
            return country_taxes('*');
        }
    }

    if (!function_exists('billing_address_tax')) {
        function billing_address_tax(array $address = [])
        {
            if (isset($address['country']) && !empty($address['country'])) {
                $stateCode = isset($address['state_code']) ? $address['state_code'] : null;
                $taxes = country_taxes($address['country'], $stateCode);
                if (count($taxes) > 0) {
                    return $taxes;
                }
            }

            return rest_of_world_tax();
        }
    }
}
