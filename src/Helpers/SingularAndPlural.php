<?php
namespace Nano\Helpers;

class SingularAndPlural
{
	# ------------------------------------------ ------------------------------------------ #
	private static function getArray()
	{
		$common = array
		(
			'activity'                     => 'activities',
			'accessory'                    => 'accessories',
			'address'                      => 'addresses',
			'body'                         => 'bodies',
			'category'                     => 'categories',
			'city'                         => 'cities',
			'person'                       => 'people',
			'freepass'                     => 'freepasses',
			'modality'                     => 'modalities',
			'recurrency'                   => 'recurrencies',
			'bonus'                        => 'bonuses',
			'service_modality'             => 'service_modalities',
			'ServiceModality'              => 'ServiceModalities',
			'plan_modality'                => 'plan_modalities',
			'PlanModality'                 => 'PlanModalities',
			'reply'                        => 'replies',
			'Reply'                        => 'Replies',
			'klass'                        => 'klasses',
			'gympass'                      => 'gympasses',
			'Gympass'                      => 'Gympasses',
		);
		
		if ( file_exists( __DIR__ . '/../nano.conf.ini' ) )
		{
			$config = parse_ini_file( __DIR__ . '/../nano.conf.ini', true );
		}
		
		if ( isset( $config['singular_and_plural'] ) && is_array( $config['singular_and_plural'] ) && count( $config['singular_and_plural'] ) )
		{
			return array_merge( $common, $config['singular_and_plural'] );
		}
		else
		{
			return $common;
		}
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public final static function convertToSingular( $word )
	{
		$singular_plural_array = self::getArray();
		$result = array_search( $word, $singular_plural_array );
		return ( $result ? $result : rtrim( $word, 's' ) );
	}
	
	# ------------------------------------------ ------------------------------------------ #
	public final static function convertToPlural( $word )
	{
		$singular_plural_array = self::getArray();
		return ( array_key_exists( $word, $singular_plural_array ) ? $singular_plural_array[$word] : $word . 's' );
	}
}
