<?php
/*
 *      OSCLass – software for creating and publishing online classified
 *                           advertising platforms
 *
 *                        Copyright (C) 2010 OSCLASS
 *
 *       This program is free software: you can redistribute it and/or
 *     modify it under the terms of the GNU Affero General Public License
 *     as published by the Free Software Foundation, either version 3 of
 *            the License, or (at your option) any later version.
 *
 *     This program is distributed in the hope that it will be useful, but
 *         WITHOUT ANY WARRANTY; without even the implied warranty of
 *        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *             GNU Affero General Public License for more details.
 *
 *      You should have received a copy of the GNU Affero General Public
 * License along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */


class Test extends DAO {
    private static $instance ;
    private $aLocales ;
    private $aCountries ;

	public static function newInstance() { 
        if(!self::$instance instanceof self) {
            self::$instance = new self ;
        }
        return self::$instance ;
    }

    public function __construct() {
        parent::__construct() ;
        
        //and locales...
        $this->aLocales = Locale::newInstance()->listAllEnabled() ;
        //and countries...
        $this->aCountries = Country::newInstance()->listAll() ;
    }

    public function getTableName() { return '' ; }

	public function loadItemInfo($email) {

        //first of all, we need the categories that we have in this installation
        $aCategories = Category::newInstance()->findRootCategories() ;
        $aIdCategories = array() ;
        $aTotalPerCategory = array() ;
        for($i = 0 ; $i < count($aCategories) ; $i++) {
            //echo $aCategories[$i]['pk_i_id'] . "#<br />" ;
            $aIdCategories[] = $aCategories[$i]['pk_i_id'] ;
        }
        
        //ITEMS
	$sql =  "INSERT INTO `oc_t_item` VALUES " ;
        $aSqlInserts = $this->getItemInserts() ;
        for ($i = 0 ; $i < count($aSqlInserts) ; $i++) {
            $aSqlInserts[$i] = str_replace('%EMAIL%', $email, $aSqlInserts[$i]) ;
            $position = rand(0, count($aIdCategories)-1) ;
            if(!isset($aTotalPerCategory[$aIdCategories[$position]])) {
                $aTotalPerCategory[$aIdCategories[$position]] = 1 ;
            } else {
                $aTotalPerCategory[$aIdCategories[$position]]++ ;
            }
            $aSqlInserts[$i] = str_replace('%CATEGORY%', $aIdCategories[$position], $aSqlInserts[$i]) ;
        }

        $sql .= implode($aSqlInserts, ",") ;
        $this->conn->osc_dbExec($sql) ;

        //ITEM DESCRIPTIONS
        $sql = "INSERT INTO `oc_t_item_description` VALUES " ;
        $aSqlInserts = $this->getItemDescriptionInserts() ;
        $aSqlInsertsWithAllTheLocales = array() ;
        for ($i = 0 ; $i < count($aSqlInserts) ; $i++) {
            for($j = 0 ; $j < count($this->aLocales) ; $j++) {
                $aSqlInsertsWithAllTheLocales[] = str_replace('%LOCALE%', $this->aLocales[$j]['pk_c_code'], $aSqlInserts[$i]) ;
            }
        }

        $sql .= implode($aSqlInsertsWithAllTheLocales, ",") ;
        $this->conn->osc_dbExec($sql) ;

        //ITEM LOCATION
        $sql = "INSERT INTO `oc_t_item_location` VALUES " ;
        $aSqlInserts = $this->getItemLocationInserts() ;
        for ($i = 0 ; $i < count($aSqlInserts) ; $i++) {
            $aSqlInserts[$i] = str_replace('%COUNTRY_CODE%', $this->aCountries[0]['pk_c_code'], $aSqlInserts[$i]) ;
            $aSqlInserts[$i] = str_replace('%COUNTRY_NAME%', $this->aCountries[0]['s_name'], $aSqlInserts[$i]) ;
        }

        $sql .= implode($aSqlInserts, ",") ;
        $this->conn->osc_dbExec($sql) ;

        //ITEM RESOURCES
        $sql = "INSERT INTO `oc_t_item_resource` VALUES " ;
        $aSqlInserts = $this->getItemResourceInserts() ;
        $sql .= implode($aSqlInserts, ",") ;
        $this->conn->osc_dbExec($sql) ;

        //CATEGORY STATS
        $aSqlInserts = array() ;
        foreach($aTotalPerCategory as $catId => $total) {
            $aSqlInserts[] = "fk_i_category_id = " . $catId . ", i_num_items = " . $total . ")" ;
        }
        $sql = "UPDATE `oc_t_category_stats` SET " ;
        $sql .= implode($aSqlInserts, ",") ;
        $this->conn->osc_dbExec($sql) ;
    }

    public function getItemInserts() {
        $fields = array ('pk_i_id', 'fk_i_user_id', 'fk_i_category_id', 'dt_pub_date', 'dt_mod_date', 'f_price', 'fk_c_currency_code', 's_contact_name', 's_contact_email', 'b_premium', 'e_status', 's_secret', 'b_show_email') ;
        $inserts = array(
                    "(1,1,%CATEGORY%,'2011-02-11 12:23:29','2011-02-11 12:23:29',223.43,NULL,'NewCorp','%EMAIL%',NULL,'ACTIVE','2fn2fsjo',0)"
                    ,"(2,1,%CATEGORY%,'2011-02-11 12:30:31','2011-02-11 12:30:31',1000,NULL,'NewCorp','%EMAIL%',NULL,'ACTIVE','nxmcojpz',0)"
                    ,"(3,1,%CATEGORY%,'2011-02-11 12:29:19','2011-02-11 12:29:19',2500,NULL,'NewCorp','%EMAIL%',NULL,'ACTIVE','b0ahftfu',0)"
                    ,"(4,1,%CATEGORY%,'2011-02-14 11:21:09','2011-02-14 11:21:09',3900,NULL,'NewCorp','%EMAIL%',NULL,'ACTIVE','bkj8jnu2',0)"
                    ,"(5,1,%CATEGORY%,'2011-02-11 12:25:31','2011-02-11 12:25:31',213.20,NULL,'NewCorp','%EMAIL%',NULL,'ACTIVE','xyqo41aj',0)"
                    ,"(6,1,%CATEGORY%,'2011-02-11 12:22:00','2011-02-11 12:22:00',412.232,NULL,'NewCorp','%EMAIL%',NULL,'ACTIVE','3rpcisj1',0)"
                    ,"(7,1,%CATEGORY%,'2011-02-11 12:13:25','2011-02-11 12:13:25',2000,NULL,'NewCorp','%EMAIL%',NULL,'ACTIVE','5jgcecci',0)"
                    ,"(8,1,%CATEGORY%,'2011-02-14 11:21:41','2011-02-14 11:21:41',5600,NULL,'NewCorp','%EMAIL%',NULL,'ACTIVE','ol0zsz0y',0)"
                    ,"(9,1,%CATEGORY%,'2011-02-11 12:24:49','2011-02-11 12:24:49',7200,NULL,'NewCorp','%EMAIL%',NULL,'ACTIVE','ejtxfj9e',0)"
                    ,"(10,1,%CATEGORY%,'2011-02-14 11:05:40','2011-02-14 11:05:40',200,NULL,'NewCorp','%EMAIL%',NULL,'ACTIVE','c66vhky2',0)"
                    ,"(11,1,%CATEGORY%,'2011-02-14 11:20:25','2011-02-14 11:20:25',NULL,NULL,'NewCorp','%EMAIL%',NULL,'ACTIVE','mxe9ouht',0)"
        ) ;
        
        return($inserts) ;
    }

    public function getItemDescriptionInserts() {
        $inserts = array(
                        "(1,'%LOCALE%','%LOCALE%Customer Service / Atenci√≥n al cliente','%LOCALE%Precisamos aumentar plantilla en el departamento de atenci√≥n al cliente.\r\n\r\n- Persona con dotes de comunicaci√≥n y h√°bil con el trato al cliente. \r\n- Familiarizado con las nuevas tecnolog√≠as: Internet, correo electr√≥nico, etc...\r\n- Realizar√° tareas de atenci√≥n al usuario de manera telef√≥nica y por escrito.\r\n\r\nNos interesa contar con personas que tengan ganas de aprender y les entusiasme el trabajo en equipo.','Customer Service / Atenci√≥n al cliente Precisamos aumentar plantilla en el departamento de atenci√≥n al cliente.\r\n\r\n- Persona con dotes de comunicaci√≥n y h√°bil con el trato al cliente. \r\n- Familiarizado con las nuevas tecnolog√≠as: Internet, correo electr√≥nico, etc...\r\n- Realizar√° tareas de atenci√≥n al usuario de manera telef√≥nica y por escrito.\r\n\r\nNos interesa contar con personas que tengan ganas de aprender y les entusiasme el trabajo en equipo.')"
                        ,"(2,'%LOCALE%','%LOCALE%Product Manager Junior','%LOCALE%Eres creativo/a y espavilado/a? ¬øTe gusta el marketing?\r\n\r\nEn NewCorp estamos buscando un Product Manager Junior para nuestro departamento de Marketing.\r\nRealizar√°s tareas de soporte a nuestras campa√±as en el √°mbito del marketing. Es un puesto donde tocar√°s muchos aspectos por lo que te ofrecemos un profundo aprendizaje en varios campos.\r\n\r\nBuscamos a una persona que est√© motivada por trabajar en el sector de internet y le guste el marketing.\r\nLos requisitos son los siguientes:\r\n\r\n- Postgrado en marketing\r\n- Experiencia de dos a√±os en marketing y almenos 8 meses en el sector del marketing online\r\n- Conocimientos de Anal√≠tica Web\r\n\r\nSi cumples con los requisitos, no lo dudes y env√≠anos tu CV!\r\n','Product Manager Junior ¬øEres creativo/a y espavilado/a? ¬øTe gusta el marketing?\r\n\r\nEn NewCorp estamos buscando un Product Manager Junior para nuestro departamento de Marketing.\r\nRealizar√°s tareas de soporte a nuestras campa√±as en el √°mbito del marketing. Es un puesto donde tocar√°s muchos aspectos por lo que te ofrecemos un profundo aprendizaje en varios campos.\r\n\r\nBuscamos a una persona que est√© motivada por trabajar en el sector de internet y le guste el marketing.\r\nLos requisitos son los siguientes:\r\n\r\n- Postgrado en marketing\r\n- Experiencia de dos a√±os en marketing y almenos 8 meses en el sector del marketing online\r\n- Conocimientos de Anal√≠tica Web\r\n\r\nSi cumples con los requisitos, no lo dudes y env√≠anos tu CV!\r\n')"
                        ,"(3,'%LOCALE%','%LOCALE%Delegado de Relaciones','%LOCALE%Buscamos profesionales Senior para desarrollar labores comerciales dentro del sector de la consultor√≠a, especializada en Pymes.\r\nPensamos en Diplomados o Titulados en ciencias empresariales,administraci√≥n de empresas,derecho, etc.\r\nCon una experiencia m√≠nima de 2 a√±os en puestos similares.\r\nPersona con gran capacidad de negociaci√≥n e inmunes al desaliento.\r\nImprescindible carne de conducir.\r\nVeh√≠culo propio.\r\nLibertad para viajar por territorio nacional (capacidad para viajar). ','Delegado de Relaciones Buscamos profesionales Senior para desarrollar labores comerciales dentro del sector de la consultor√≠a, especializada en Pymes.\r\nPensamos en Diplomados o Titulados en ciencias empresariales,administraci√≥n de empresas,derecho, etc.\r\nCon una experiencia m√≠nima de 2 a√±os en puestos similares.\r\nPersona con gran capacidad de negociaci√≥n e inmunes al desaliento.\r\nImprescindible carne de conducir.\r\nVeh√≠culo propio.\r\nLibertad para viajar por territorio nacional (capacidad para viajar). ')"
                        ,"(4,'%LOCALE%','%LOCALE%Abogado - Asesor Jur√≠dico','%LOCALE%Seleccionamos para nuestra Asesor√≠a Jur√≠dica un abogado con 5 a√±os de experiencia. La persona seleccionada formar√° parte de nuestro equipo de abogados dando soporte a los mismos en todo lo relacionado principalmente con Responsabilidad Civil','Abogado - Asesor Jur√≠dico Seleccionamos para nuestra Asesor√≠a Jur√≠dica un abogado con 5 a√±os de experiencia. La persona seleccionada formar√° parte de nuestro equipo de abogados dando soporte a los mismos en todo lo relacionado principalmente con Responsabilidad Civil')"
                        ,"(5,'%LOCALE%','%LOCALE%Consultoras en Desarrollo y Liderazgo','%LOCALE%Profesionales para intervenir en proyectos de acompa√±amiento a responsables de liderar e implantar procesos de cambio o transformaci√≥n en sus organizaciones, facilitando su desarrollo competencial. ','Consultoras en Desarrollo y Liderazgo Profesionales para intervenir en proyectos de acompa√±amiento a responsables de liderar e implantar procesos de cambio o transformaci√≥n en sus organizaciones, facilitando su desarrollo competencial. ')"
                        ,"(6,'%LOCALE%','%LOCALE%Asesora Comercial Secretaria depto. Legal','%LOCALE%Deber√° tener experiencia comprobable en el puesto y marcada orientaci√≥n al cliente:\r\n\r\n-atenci√≥n al cliente externo/interno.\r\n-gesti√≥n de cobranza mora temprana.\r\n-gesti√≥n administrativa (armado de planillas, facturaci√≥n etc).\r\n\r\nTrabajar√° para el departamento Legal','Asesora Comercial Secretaria depto. Legal Deber√° tener experiencia comprobable en el puesto y marcada orientaci√≥n al cliente:\r\n\r\n-atenci√≥n al cliente externo/interno.\r\n-gesti√≥n de cobranza mora temprana.\r\n-gesti√≥n administrativa (armado de planillas, facturaci√≥n etc).\r\n\r\nTrabajar√° para el departamento Legal')"
                        ,"(7,'%LOCALE%','%LOCALE%Asesora Comercial Secretaria depto. Mercantil','%LOCALE%Deber√° tener experiencia comprobable en el puesto y marcada orientaci√≥n al cliente:\r\n\r\n-atenci√≥n al cliente externo/interno.\r\n-gesti√≥n de cobranza mora\r\n-gesti√≥n administrativa (armado de planillas, facturaci√≥n etc).\r\n\r\nTrabajar√° para el departamento Mercantil','Asesora Comercial Secretaria depto. Mercantil Deber√° tener experiencia comprobable en el puesto y marcada orientaci√≥n al cliente:\r\n\r\n-atenci√≥n al cliente externo/interno.\r\n-gesti√≥n de cobranza mora\r\n-gesti√≥n administrativa (armado de planillas, facturaci√≥n etc).\r\n\r\nTrabajar√° para el departamento Mercantil')"
                        ,"(8,'%LOCALE%','%LOCALE%Delegado Comercial Zona Buenos Aires','%LOCALE%Bucamos ejecutivo comercial para comercializar servicios jur√≠dicos, con orientaci√≥n a resultados','Delegado Comercial Zona Buenos Aires Bucamos ejecutivo comercial para comercializar servicios jur√≠dicos, con orientaci√≥n a resultados')"
                        ,"(9,'%LOCALE%','%LOCALE%Delegado Comercial C√≥rdoba','%LOCALE%Bucamos ejecutivo comercial para comercializar servicios jur√≠dicos, con orientaci√≥n a resultados\r\n','Delegado Comercial C√≥rdoba Bucamos ejecutivo comercial para comercializar servicios jur√≠dicos, con orientaci√≥n a resultados\r\n')"
                        ,"(10,'%LOCALE%','%LOCALE%Abogado / Consultor laboral','%LOCALE%Nuestra b√∫squeda se orienta a un abogado con experiencia en consultor√≠a laboral y previsional, conflictos judiciales laborales y convenios colectivos de trabajo.\r\n\r\nSus responsabilidades dentro de NewCorp ser√°n el asesoramiento a otras empresas del grupo en el √°rea laboral, seguridad social, obras sociales y redacci√≥n editorial.\r\n\r\nEdad: 24 - 40 a√±os\r\nLos interesados deber√°n enviar su CV sin omitir la remuneraci√≥n pretendida.','Abogado / Consultor laboral Nuestra b√∫squeda se orienta a un abogado con experiencia en consultor√≠a laboral y previsional, conflictos judiciales laborales y convenios colectivos de trabajo.\r\n\r\nSus responsabilidades dentro de NewCorp ser√°n el asesoramiento a otras empresas del grupo en el √°rea laboral, seguridad social, obras sociales y redacci√≥n editorial.\r\n\r\nEdad: 24 - 40 a√±os\r\nLos interesados deber√°n enviar su CV sin omitir la remuneraci√≥n pretendida.')"
                        ,"(11,'%LOCALE%','%LOCALE%Consultor funcional SAP','%LOCALE%NewCorp se encuentra en la b√∫squeda de profesionales con experiencia como Consultores Funcionales Sap Pp/ Sap Ps/ Sap Pm y Sap Mm, para formar parte de diversos proyectos en el exterior, para una Importante Multinacional en Tecnolog√≠a.\r\n\r\n\r\n Horario de Trabajo: 9 a 18 hs.\r\n Fecha de incorporaci√≥n: Inmediata\r\n\r\n Se ofrecen excelentes condiciones de contrataci√≥n, posibilidad de trabajar en el exterior y desarrollo de carrera.\r\n\r\nLos interesados deber√°n enviar un mail con Ref.: SD en el asunto, indicando su remuneraci√≥n bruta pretendida y disponibilidad para entrevistas a clara_zito@bnetbuilders.com','Consultor funcional SAP NewCorp se encuentra en la b√∫squeda de profesionales con experiencia como Consultores Funcionales Sap Pp/ Sap Ps/ Sap Pm y Sap Mm, para formar parte de diversos proyectos en el exterior, para una Importante Multinacional en Tecnolog√≠a.\r\n\r\n\r\n Horario de Trabajo: 9 a 18 hs.\r\n Fecha de incorporaci√≥n: Inmediata\r\n\r\n Se ofrecen excelentes condiciones de contrataci√≥n, posibilidad de trabajar en el exterior y desarrollo de carrera.\r\n\r\nLos interesados deber√°n enviar un mail con Ref.: SD en el asunto, indicando su remuneraci√≥n bruta pretendida y disponibilidad para entrevistas a clara_zito@bnetbuilders.com')"
        ) ;

        return($inserts) ;
    }

    public function getItemLocationInserts() {
        $fields = array('fk_i_item_id', 'fk_c_country_code', 's_country', 's_address', 's_zip', 'fk_i_region_id', 's_region', 'fk_i_city_id', 's_city', 'fk_i_city_area_id', 's_city_area', 'd_coord_lat', 'd_coord_long') ;
        $inserts = array(
                        "(1,'%COUNTRY_CODE%','%COUNTRY_NAME%','',NULL,NULL,'Buenos Aires',NULL,'Capital Federal',NULL,'',NULL,NULL)"
                        ,"(2,'%COUNTRY_CODE%','%COUNTRY_NAME%','',NULL,NULL,'Buenos Aires',NULL,'Capital Federal',NULL,'',NULL,NULL)"
                        ,"(3,'%COUNTRY_CODE%','%COUNTRY_NAME%','',NULL,NULL,'Buenos Aires',NULL,'Capital Federal',NULL,'',NULL,NULL)"
                        ,"(4,'%COUNTRY_CODE%','%COUNTRY_NAME%','',NULL,NULL,'Buenos Aires',NULL,'Capital Federal',NULL,'',NULL,NULL)"
                        ,"(5,'%COUNTRY_CODE%','%COUNTRY_NAME%','',NULL,NULL,'Mendoza',NULL,'Mendoza',NULL,'',NULL,NULL)"
                        ,"(6,'%COUNTRY_CODE%','%COUNTRY_NAME%','',NULL,NULL,'Buenos Aires',NULL,'Capital Federal',NULL,'',NULL,NULL)"
                        ,"(7,'%COUNTRY_CODE%','%COUNTRY_NAME%','',NULL,NULL,'Buenos Aires',NULL,'Capital Federal',NULL,'',NULL,NULL)"
                        ,"(8,'%COUNTRY_CODE%','%COUNTRY_NAME%','',NULL,NULL,'Buenos Aires',NULL,'Zona Norte',NULL,'',NULL,NULL)"
                        ,"(9,'%COUNTRY_CODE%','%COUNTRY_NAME%','',NULL,NULL,'Cordoba',NULL,'Cosquin',NULL,'',NULL,NULL)"
                        ,"(10,'%COUNTRY_CODE%','%COUNTRY_NAME%','',NULL,NULL,'Buenos Aires',NULL,'Capital Federal',NULL,'Puerto Madero',NULL,NULL)"
                        ,"(11,'%COUNTRY_CODE%','%COUNTRY_NAME%','',NULL,NULL,'Buenos Aires',NULL,'San Isidro',NULL,'',NULL,NULL)"
        ) ;

        return($inserts) ;
    }

    public function getItemResourceInserts() {
        $fields = array('pk_i_id', 'fk_i_item_id', 's_name', 's_extension', 's_content_type', 's_path') ;
        $inserts = array(
                        "(1,1,'1','png','image/png','oc-content/uploads/test/')"
                        ,"(2,1,'2','png','image/png','oc-content/uploads/test/')"
                        ,"(3,1,'3','png','image/png','oc-content/uploads/test/')"
                        ,"(4,1,'4','png','image/png','oc-content/uploads/test/')"
                        ,"(5,2,'5','png','image/png','oc-content/uploads/test/')"
                        ,"(6,2,'6','png','image/png','oc-content/uploads/test/')"
                        ,"(7,3,'7','png','image/png','oc-content/uploads/test/')"
                        ,"(8,4,'8','png','image/png','oc-content/uploads/test/')"
                        ,"(9,5,'9','png','image/png','oc-content/uploads/test/')"
                        ,"(10,6,'10','png','image/png','oc-content/uploads/test/')"
                        ,"(11,6,'11','png','image/png','oc-content/uploads/test/')"
        ) ;

        return($inserts) ;
    }
    
    public function loadUserInfo($email, $name) {
        //USERS
        $sql = "INSERT INTO `oc_t_user` VALUES " ;
        $aSqlInserts = $this->getUserInserts() ;
        for ($i = 0 ; $i < count($aSqlInserts) ; $i++) {
            $aSqlInserts[$i] = str_replace('%EMAIL%', $email, $aSqlInserts[$i]) ;
            $aSqlInserts[$i] = str_replace('%NAME%', $name, $aSqlInserts[$i]) ;
        }

        $sql .= implode($aSqlInserts, ",") ;
        $this->conn->osc_dbExec($sql) ;

        //USER DESCRIPTIONS
        $sql = "INSERT INTO `oc_t_user_description` VALUES " ;
        $aSqlInserts = $this->getUserDescriptionInserts() ;
        for ($i = 0 ; $i < count($aSqlInserts) ; $i++) {
            for($j = 0 ; $j < count($this->aLocales) ; $j++) {
                $aSqlInsertsWithAllTheLocales[] = str_replace('%LOCALE%', $this->aLocales[$j]['pk_c_code'], $aSqlInserts[$i]) ;
            }
        }

        $sql .= implode($aSqlInsertsWithAllTheLocales, ",") ;
        $this->conn->osc_dbExec($sql) ;
    }

    public function getUserInserts() {
        $inserts = array (
                        "(1, NOW(), NULL,'%NAME%','a94a8fe5ccb19ba61c4c0873d391e987982fbbd3','mvqdnrpt','%EMAIL%','http://www.danielgimenez.net','933978100','607787640',1,NULL,NULL,NULL,NULL,NULL,'ES','Spain','Av. Alfons XIII 352',NULL,3,'Barcelona',3,'Sabadell',NULL,'La Salut',NULL,NULL,'0', '0')"
        ) ;

        return($inserts) ;
    }

    public function getUserDescriptionInserts() {
        $inserts = array (
                        "(1,'%LOCALE%','Esta es la descripcion que ponemos')"
        ) ;

        return($inserts) ;
    }
}

?>
