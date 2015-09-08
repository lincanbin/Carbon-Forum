<?php


class SearchClient {
    private static $singleSelf;

    /**
     * sphinx api对象
     * @var sphinx object
     *
     */
    private static $sphinx;
    private function __construct() {
        if( !defined( 'SearchServer' ) || !defined( 'SearchPort' ) ) {
            throw new Exception("Search server or search port must be set!");
        }
        self::$sphinx    = new SphinxClient();
        self::$sphinx->setServer( SearchServer, SearchPort );
        self::$sphinx->SetConnectTimeout ( 1 );
    }
    private static function getSingle() {
        self::$singleSelf OR self::$singleSelf = new self();
        return self::$singleSelf;
    }
    public static function searchLike( $pKeywords, $index='*', $pStart = 0, 
                            $pSize=12, $filter = '', $sort = '', $columns=array("ID") ) {
        self::getSingle();
        $sp = self::$sphinx;
        empty( $sort ) || $sp->SetSortMode ( SPH_SORT_EXTENDED, $sort );
        if ($filter) {
            $filter['select']  && $sp->SetSelect( '*,'.$filter['select'] );
            foreach( $filter['filter'] as $key => &$row) {
                $sp->SetFilter ( $key, $row['val'],
                        isset($row['rev']) ? $row['rev']: false );
            }
        }
        $sp->SetLimits ( $pStart, $pSize, 10000 );
        $sp->SetArrayResult ( true );
        $res = $sp->query( $pKeywords, $index );
        $sp->ResetFilters();
        $sp->ResetGroupBy();
        if ( $res === false ) {
            throw new Exception( $sp->getLastError() );
        } else  {
            $searchList = array();
            foreach ( $res["matches"] as &$row) {
                $searchList['id'][] = $row['id'];
                foreach( $columns as $col ) {
                    $col = strtolower($col);
                    if( isset( $row['attrs'][$col] ) ) {
                        $searchList[$col][] = $row['attrs'][$col];
                    }
                }
            }
            return array( $searchList, $res['total'] );
        }
    }
    public static function searchCount( $pKeywords, $index='*', $pStart = 0, 
                            $pSize=12, $filter = '', $sort = '' ) {
        $tRes = self::searchLike( $pKeywords,  $index, $pStart,
                            $pSize, $filter, $sort );
        return empty( $tRes ) ? 0 : $tRes[1];
    }
    public static function callProxy( $spMethod, $args) {
        self::getSingle();
        $sp = self::$sphinx;
        return call_user_func_array(array($sp, $spMethod),
            $args);
    }
}
