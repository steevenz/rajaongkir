<?php
/**
 * Advanced RajaOngkir PHP API Class
 *
 * Copyright (C) 2015  Steeve Andrian Salim (steevenz)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author         Steeve Andrian Salim
 * @copyright      Copyright (c) 2015, Steeve Andrian Salim
 * @since          Version 1.1.0
 * @filesource
 */

// ------------------------------------------------------------------------

namespace Steevenz;

// ------------------------------------------------------------------------

use O2System\CURL;
use O2System\CURL\Interfaces\Method;

class Rajaongkir
{
	/**
	 * Constant Account Type
	 *
	 * @access  public
	 * @type    string
	 */
	const ACCOUNT_STARTER = 'starter';
	const ACCOUNT_BASIC   = 'basic';
	const ACCOUNT_PRO     = 'pro';

	/**
	 * Rajaongkir Account Type
	 *
	 * @access  protected
	 * @type    string
	 */
	protected $_account_type = 'starter';

	/**
	 * API Key
	 *
	 * @access  protected
	 * @type    string
	 */
	protected $_api_key = NULL;

	/**
	 * API URL
	 *
	 * @access  protected
	 * @type    string
	 */
	protected $_api_url = NULL;

	/**
	 * List of API URLs Endpoint
	 *
	 * @access  protected
	 * @type    array
	 */
	protected $_api_urls = array(
		'starter' => 'http://rajaongkir.com/api/starter/',
		'basic'   => 'http://rajaongkir.com/api/basic/',
		'pro'     => 'http://pro.rajaongkir.com/api/',
	);

	/**
	 * List of Supported Account Types
	 *
	 * @access  protected
	 * @type    array
	 */
	protected $_supported_account_types = array(
		'starter',
		'basic',
		'pro',
	);

	/**
	 * Supported Couriers
	 *
	 * @access  protected
	 * @type    array
	 */
	protected $_supported_couriers = array(
		'starter' => array(
			'jne',
			'pos',
			'tiki',
		),
		'basic'   => array(
			'jne',
			'pos',
			'tiki',
			'pcp',
			'esl',
			'rpx',
		),
		'pro'     => array(
			'jne',
			'pos',
			'tiki',
			'rpx',
			'esl',
			'pcp',
			'pandu',
			'wahana',
		),
	);

	/**
	 * Supported Waybills
	 *
	 * @access  protected
	 * @type    array
	 */
	protected $_supported_waybills = array(
		'starter' => array(),
		'basic'   => array(
			'jne',
		),
		'pro'     => array(
			'jne',
		),
	);

	/**
	 * Courier List
	 *
	 * @access  protected
	 * @type array
	 */
	protected $_couriers_list = array(
		'jne'    => 'Jalur Nugraha Ekakurir (JNE)',
		'pos'    => 'POS Indonesia (POS)',
		'tiki'   => 'Citra Van Titipan Kilat (TIKI)',
		'pcp'    => 'Priority Cargo and Package (PCP)',
		'esl'    => 'Eka Sari Lorena (ESL)',
		'rpx'    => 'RPX Holding (RPX)',
		'pandu'  => 'Pandu Logistics (PANDU)',
		'wahana' => 'Wahana Prestasi Logistik (WAHANA)',
	);

	/**
	 * O2System CURL Resource
	 *
	 * @access  protected
	 * @type    \O2System\CURL
	 */
	protected $_curl;

	/**
	 * RajaOngkir Original Response
	 *
	 * @access  protected
	 * @type    mixed
	 */
	protected $_response;

	/**
	 * RajaOngkir Errors
	 *
	 * @access  protected
	 * @type    array
	 */
	protected $_errors = array();

	// ------------------------------------------------------------------------

	/**
	 * Class Constructor
	 *
	 * @access  public
	 * @throws  \InvalidArgumentException
	 */
	public function __construct( $api_key = NULL, $account_type = NULL )
	{
		if ( isset( $api_key ) )
		{
			if ( is_array( $api_key ) )
			{
				if ( isset( $api_key[ 'api_key' ] ) )
				{
					$this->_api_key = $api_key[ 'api_key' ];
				}

				if ( isset( $api_key[ 'account_type' ] ) )
				{
					$account_type = $api_key[ 'account_type' ];
				}
			}
			elseif ( is_string( $api_key ) )
			{
				$this->_api_key = $api_key;
			}
		}

		if ( isset( $account_type ) )
		{
			$this->_account_type = strtolower( $account_type );
		}
		else
		{
			$this->_account_type = 'starter';
		}

		if ( array_key_exists( $this->_account_type, $this->_api_urls ) )
		{
			$this->_api_url = $this->_api_urls[ $this->_account_type ];
		}
		else
		{
			throw new \InvalidArgumentException( 'Rajaongkir: Invalid Account Type' );
		}

		/*
		 * ------------------------------------------------------
		 *  Initialized O2System CURL Class
		 * ------------------------------------------------------
		 */
		$this->_curl = new CURL();
	}

	// ------------------------------------------------------------------------

	/**
	 * Set API Key
	 *
	 * @param   string $api_key RajaOngkir API Key
	 *
	 * @access  public
	 * @return  object
	 */
	public function set_api_key( $api_key )
	{
		$this->_api_key = $api_key;

		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * Set Account Type
	 *
	 * @param   string $account_type RajaOngkir Account Type, can be starter, basic or pro
	 *
	 * @access  public
	 * @return  object
	 * @throws  \InvalidArgumentException
	 */
	public function set_account_type( $account_type )
	{
		$account_type = strtolower( $account_type );

		if ( array_key_exists( $account_type, $this->_api_urls ) )
		{
			$this->_account_type = $account_type;
			$this->_api_url = $this->_api_urls[ $this->_account_type ];
		}
		else
		{
			throw new \InvalidArgumentException( 'Rajaongkir: Invalid Account Type' );
		}

		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * API Request
	 *
	 * @param string $path
	 * @param array  $params
	 * @param string $type
	 *
	 * @access  protected
	 * @return  mixed
	 */
	protected function _request( $path, $params = array(), $type = Method::GET )
	{
		$headers[ 'key' ] = $this->_api_key;

		switch ( $type )
		{
			default:
			case 'GET':
				$this->_response = $this->_curl->get( $this->_api_url, $path, $params, $headers );
				break;

			case 'POST':
				$headers[ 'content-type' ] = 'application/x-www-form-urlencoded';
				$this->_response = $this->_curl->post( $this->_api_url, $path, $params, $headers );
				break;
		}

		if ( $this->_response->meta->http_code === 200 )
		{
			if ( isset( $this->_response->body->rajaongkir->results ) )
			{
				$result = $this->_response->body->rajaongkir->results;

				if ( is_array( $result ) AND count( $result ) == 1 )
				{
					return reset( $result );
				}
				else
				{
					return $result;
				}
			}
			elseif ( isset( $this->_response->body->rajaongkir->result ) )
			{
				return $this->_response->body->rajaongkir->result;
			}
			else
			{
				if ( isset( $params[ 'origin' ] ) AND isset( $params[ 'destination' ] ) )
				{
					$this->_errors[ 400 ] = 'Invalid origin / destination ID. ID origin / destination tidak ditemukan di database RajaOngkir.';
				}
				else
				{
					$this->_errors[ 400 ] = 'Invalid Response. RajaOngkir tidak memberikan respon yang sah.';
				}
			}
		}
		else
		{
			$this->_errors[ $this->_response->body->rajaongkir->status->code ] = $this->_response->body->rajaongkir->status->description;
		}

		return FALSE;
	}

	// ------------------------------------------------------------------------

	/**
	 * Get Couriers List
	 *
	 * @access  public
	 * @return  array
	 */
	public function get_couriers_list()
	{
		return $this->_couriers_list;
	}

	// ------------------------------------------------------------------------

	/**
	 * Get Provinces
	 *
	 * @access  public
	 * @return  mixed
	 */
	public function get_provinces()
	{
		return $this->_request( 'province' );
	}

	// ------------------------------------------------------------------------

	/**
	 * Get Provinces
	 *
	 * @param   int $id_province Province ID
	 *
	 * @access  public
	 * @return  mixed
	 */
	public function get_province( $id_province )
	{
		return $this->_request( 'province', [ 'id' => $id_province ] );
	}

	// ------------------------------------------------------------------------

	/**
	 * Get Cities
	 *
	 * @param   int $id_province Province ID
	 *
	 * @access  public
	 * @return  mixed
	 */
	public function get_cities( $id_province = NULL )
	{
		$params = array();

		if ( ! is_null( $id_province ) )
		{
			$params[ 'province' ] = $id_province;
		}

		return $this->_request( 'city', $params );
	}

	// ------------------------------------------------------------------------

	/**
	 * Get City
	 *
	 * @param   int $id_city City ID
	 *
	 * @access  public
	 * @return  mixed
	 */
	public function get_city( $id_city )
	{
		return $this->_request( 'city', [ 'id' => $id_city ] );
	}

	// ------------------------------------------------------------------------

	/**
	 * Get Subdisctricts
	 *
	 * @param   int $id_city City ID
	 *
	 * @access  public
	 * @return  mixed
	 */
	public function get_subdistricts( $id_city )
	{
		return $this->_request( 'subdistrict', [ 'city' => $id_city ] );
	}

	// ------------------------------------------------------------------------

	/**
	 * Get Subdisctrict
	 *
	 * @param   int $id_subdistrict Subdistrict ID
	 *
	 * @access  public
	 * @return  mixed
	 */
	public function get_subdistrict( $id_subdistrict )
	{
		return $this->_request( 'subdistrict', [ 'id' => $id_subdistrict ] );
	}

	// ------------------------------------------------------------------------

	/**
	 * Get International Origins
	 *
	 * @param   int $id_province Province ID
	 *
	 * @access  public
	 * @return  mixed
	 */
	public function get_international_origins( $id_province = NULL )
	{
		if ( $this->_account_type === 'starter' ) return FALSE;

		$params = array();

		if ( isset( $id_province ) )
		{
			$params[ 'province' ] = $id_province;
		}

		return $this->_request( 'internationalOrigin', $params );
	}

	// ------------------------------------------------------------------------

	/**
	 * Get International Origin
	 *
	 * @param   int $id_city City ID
	 *
	 * @access  public
	 * @return  mixed
	 */
	public function get_international_origin( $id_city = NULL )
	{
		if ( $this->_account_type === 'starter' ) return FALSE;

		return $this->_request( 'internationalOrigin', [ 'id' => $id_city ] );
	}

	// ------------------------------------------------------------------------

	/**
	 * Get International Destinations
	 *
	 * @param   int $id_country Country ID
	 *
	 * @access  public
	 * @return  mixed
	 */
	public function get_international_destinations()
	{
		if ( $this->_account_type === 'starter' ) return FALSE;

		return $this->_request( 'internationalDestination' );
	}

	/**
	 * Get International Destination
	 *
	 * @param   int $id_country Country ID
	 *
	 * @access  public
	 * @return  mixed
	 */
	public function get_international_destination( $id_country )
	{
		if ( $this->_account_type === 'starter' ) return FALSE;

		$params = array();

		if ( isset( $id_country ) )
		{
			$params[ 'id' ] = $id_country;
		}

		return $this->_request( 'internationalDestination', $params );
	}

	// ------------------------------------------------------------------------

	/**
	 * Get Cost
	 *
	 * @example
	 * $rajaongkir->get_cost(
	 *      ['city' => 1],
	 *      ['subdistrict' => 12],
	 *      ['weight' => 100, 'length' => 100, 'width' => 100, 'height' => 100, 'diameter' => 100],
	 *      'jne'
	 * );
	 *
	 * @see      http://rajaongkir.com/dokumentasi/pro
	 *
	 * @param array  $origin            City, District or Subdistrict Origin
	 * @param array  $destination       City, District or Subdistrict Destination
	 * @param array  $metrics           Array of Specification
	 *                                  weight      int     weight in gram (required)
	 *                                  length      number  package length dimension
	 *                                  width       number  package width dimension
	 *                                  height      number  package height dimension
	 *                                  diameter    number  package diameter
	 * @param string $courier           Courier Code
	 *
	 * @access   public
	 * @return  mixed
	 */
	public function get_cost( array $origin, array $destination, $metrics, $courier )
	{
		$params[ 'courier' ] = strtolower( $courier );

		$params[ 'originType' ] = strtolower( key( $origin ) );
		$params[ 'destinationType' ] = strtolower( key( $destination ) );

		if ( $params[ 'originType' ] !== 'city' )
		{
			$params[ 'originType' ] = 'subdistrict';
		}

		if ( ! in_array( $params[ 'destinationType' ], [ 'city', 'country' ] ) )
		{
			$params[ 'destinationType' ] = 'subdistrict';
		}

		if ( is_array( $metrics ) )
		{
			if ( ! isset( $metrics[ 'weight' ] ) AND
				isset( $metrics[ 'length' ] ) AND
				isset( $metrics[ 'width' ] ) AND
				isset( $metrics[ 'height' ] )
			)
			{
				$metrics[ 'weight' ] = ( ( $metrics[ 'length' ] * $metrics[ 'width' ] * $metrics[ 'height' ] ) / 6000 ) * 1000;
			}
			elseif ( isset( $metrics[ 'weight' ] ) AND
				isset( $metrics[ 'length' ] ) AND
				isset( $metrics[ 'width' ] ) AND
				isset( $metrics[ 'height' ] )
			)
			{
				$weight = ( ( $metrics[ 'length' ] * $metrics[ 'width' ] * $metrics[ 'height' ] ) / 6000 ) * 1000;

				if ( $weight > $metrics[ 'weight' ] )
				{
					$metrics[ 'weight' ] = $weight;
				}
			}

			foreach ( $metrics as $key => $value )
			{
				$params[ $key ] = $value;
			}
		}
		elseif ( is_numeric( $metrics ) )
		{
			$params[ 'weight' ] = $metrics;
		}

		switch ( $this->_account_type )
		{
			case 'starter':

				if ( $params[ 'destinationType' ] === 'country' )
				{
					$this->_errors[ 301 ] = 'Unsupported International Destination. Tipe akun starter tidak mendukung pengecekan destinasi international.';

					return FALSE;
				}
				elseif ( $params[ 'originType' ] === 'subdistrict' OR $params[ 'destinationType' ] === 'subdistrict' )
				{
					$this->_errors[ 302 ] = 'Unsupported Subdistrict Origin-Destination. Tipe akun starter tidak mendukung pengecekan ongkos kirim sampai kecamatan.';

					return FALSE;
				}

				if ( ! isset( $params[ 'weight' ] ) AND
					isset( $params[ 'length' ] ) AND
					isset( $params[ 'width' ] ) AND
					isset( $params[ 'height' ] )
				)
				{
					$this->_errors[ 304 ] = 'Unsupported Dimension. Tipe akun starter tidak mendukung pengecekan biaya kirim berdasarkan dimensi.';

					return FALSE;
				}
				elseif ( isset( $params[ 'weight' ] ) AND $params[ 'weight' ] > 30000 )
				{
					$this->_errors[ 305 ] = 'Unsupported Weight. Tipe akun starter tidak mendukung pengecekan biaya kirim dengan berat lebih dari 30000 gram (30kg).';

					return FALSE;
				}

				if ( ! in_array( $params[ 'courier' ], $this->_supported_couriers[ $this->_account_type ] ) )
				{
					$this->_errors[ 303 ] = 'Unsupported Courier. Tipe akun starter tidak mendukung pengecekan biaya kirim dengan kurir ' . $this->_couriers_list[ $courier ] . '.';

					return FALSE;
				}

				break;

			case 'basic':

				if ( $params[ 'originType' ] === 'subdistrict' OR $params[ 'destinationType' ] === 'subdistrict' )
				{
					$this->_errors[ 302 ] = 'Unsupported Subdistrict Origin-Destination. Tipe akun basic tidak mendukung pengecekan ongkos kirim sampai kecamatan.';

					return FALSE;
				}

				if ( ! isset( $params[ 'weight' ] ) AND
					isset( $params[ 'length' ] ) AND
					isset( $params[ 'width' ] ) AND
					isset( $params[ 'height' ] )
				)
				{
					$this->_errors[ 304 ] = 'Unsupported Dimension. Tipe akun basic tidak mendukung pengecekan biaya kirim berdasarkan dimensi.';

					return FALSE;
				}
				elseif ( isset( $params[ 'weight' ] ) AND $params[ 'weight' ] > 30000 )
				{
					$this->_errors[ 305 ] = 'Unsupported Weight. Tipe akun basic tidak mendukung pengecekan biaya kirim dengan berat lebih dari 30000 gram (30kg).';

					return FALSE;
				}
				elseif ( isset( $params[ 'weight' ] ) AND $params[ 'weight' ] < 30000 )
				{
					unset( $params[ 'length' ], $params[ 'width' ], $params[ 'height' ] );
				}

				if ( ! in_array( $params[ 'courier' ], $this->_supported_couriers[ $this->_account_type ] ) )
				{
					$this->_errors[ 303 ] = 'Unsupported Courier. Tipe akun basic tidak mendukung pengecekan biaya kirim dengan kurir ' . $this->_couriers_list[ $courier ] . '.';

					return FALSE;
				}

				break;
		}

		$params[ 'origin' ] = $origin[ key( $origin ) ];
		$params[ 'destination' ] = $destination[ key( $destination ) ];

		$path = key( $destination ) === 'country' ? 'internationalCost' : 'cost';

		return $this->_request( $path, $params, Method::POST );
	}

	// ------------------------------------------------------------------------

	/**
	 * Get Waybill
	 *
	 * @param   int         $id_waybill Receipt ID
	 * @param   null|string $courier    Courier Code
	 *
	 * @access  public
	 * @return  mixed
	 */
	public function get_waybill( $id_waybill, $courier )
	{
		$courier = strtolower( $courier );

		if ( in_array( $courier, $this->_supported_waybills[ $this->_account_type ] ) )
		{
			return $this->_request( 'waybill', array(
				'key'     => $this->_api_key,
				'waybill' => $id_waybill,
				'courier' => $courier,
			), Method::POST );
		}

		return FALSE;
	}

	// ------------------------------------------------------------------------

	/**
	 * Get Currency
	 *
	 * @access  public
	 * @return  mixed
	 */
	public function get_currency()
	{
		if ( $this->_account_type !== 'starter' )
		{
			return $this->_request( 'currency' );
		}

		return FALSE;
	}

	// ------------------------------------------------------------------------

	/**
	 * Get Response
	 *
	 * @param   string $offset Response Offset Object
	 *
	 * @access  public
	 * @return  array
	 */
	public function get_response( $offset = NULL )
	{
		return isset( $offset ) && isset( $this->_response->{$offset} ) ? $this->_response->{$offset} : $this->_response;
	}

	// ------------------------------------------------------------------------

	/**
	 * Get Errors
	 *
	 * @access  public
	 * @return  array
	 */
	public function get_errors()
	{
		return $this->_errors;
	}
}
