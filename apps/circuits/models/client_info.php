<?php

include_once 'libs/Model/model.php';

class client_info extends Model {

    var $displayField = "alias";

    public function client_info() {
        $this->setTableName("client_info");

        // Add all table attributes
        $this->addAttribute("cli_id", "INTEGER", true, false, false);

        $this->addAttribute("alias", "VARCHAR");

        $this->addAttribute("ip_dcn", "VARCHAR");
        $this->addAttribute("ip_internet", "VARCHAR");
        $this->addAttribute("mac_address", "VARCHAR");

        $this->addAttribute("urn_id", "INTEGER");
    }

    /**
     *
     * @param string $reference A string representing an IP address, an alias or a MAC address
     * @return EndpointObject Returns the endpoint object if the query was sucessful, false othewise
     */
    static public function getBestEndpoint($reference) {
        Log::write("debug", "calculando edp: " . $reference);
        if (!$reference) {
            return false;
        }

        //urn:ogf:network:domain=cipo.pop-sc.rnp.br:node=sw4-remep:port=0-6-5:link=*
        $parts = explode(":", $reference);
        $urnObj = null;

        if (@strtoupper($parts[0]) == "URN") {
            // it's a URN
            $urn_info = new urn_info();
            $urn_info->urn_string = $reference;
            if ($urn_res = $urn_info->fetch(false)) {
                // URN was found in database
                $urnObj = $urn_res[0];
            } else {
                // try to get information from URN - partially filling in
                $hasDomain = stripos($reference, 'domain=') === false ? false : true;
                $hasNode = stripos($reference, 'node=') === false ? false : true;
                $hasPort = stripos($reference, 'port=') === false ? false : true;

                if ($hasDomain) {
                    $endpoint = new stdClass();
                    $endpoint->domain = -1;
                    $endpoint->network = null;
                    $endpoint->device = $hasNode ? -1 : null;
                    $endpoint->port = $hasPort ? -1 : null;

                    // try domain
                    $dom = new domain_info();
                    if ($domain = $dom->getOSCARSDomain($reference)) {
                        $endpoint->domain = $domain->dom_id;

                        // try device
                        $dev = new device_info();
                        if ($device = $dev->getDeviceFromNode($domain->dom_id, $reference)) {
                            $endpoint->network = $device->net_id;
                            $endpoint->device = $device->dev_id;

                            // try port
                            $urn = new urn_info();
                            if ($port = $urn->verifyValidPort($device->dev_id, $reference)) {
                                $endpoint->port = $port;
                            }
                        }
                    }
                    
                    return $endpoint;
                } else
                    return false;
            }
        } else {

            $sql = "SELECT * FROM `client_info`";
            $sql .= " WHERE `alias`='$reference' OR `ip_dcn`='$reference' OR `ip_internet`='$reference' OR `mac_address`='$reference'";
            $result = parent::querySql($sql, 'client_info');

            if ($result) {
                //Log::write("debug", "achou no banco");
                if (count($result) == 1) {
                    // retornou apenas um resultado
                    $urn_info = new urn_info();
                    $urn_info->urn_id = $result[0]->urn_id;
                    if ($urn_res = $urn_info->fetch(false)) {
                        $urnObj = $urn_res[0];
                    } else
                        return false;
                } else {
                    // tratar ambiguidade
                    Log::write("debug", "ambiguo");
                    return false;
                }
            } else {
                // try step 2 -> dynamic
                Log::write("debug", "nao achou, deve seguir passo 2");
                return false;
            }
        }

        if (is_a($urnObj, 'urn_info')) {
            $dom_id = -1;
            $aco = new Acos($urnObj->net_id, 'network_info');
            if ($aco_parent = $aco->getParentNodes()) {
                if ($aco_parent[0]->model = 'domain_info') {
                    $dom_id = $aco_parent[0]->obj_id;
                }
            }

            $endpoint = new stdClass();
            $endpoint->domain = $dom_id;
            $endpoint->network = $urnObj->net_id;
            $endpoint->device = $urnObj->dev_id;
            $endpoint->port = $urnObj->port;

            // se precisar pegar mais detalhes do endpoint
            //$endpoint = MeicanTopology::getURNDetails($dom_id, $urn->urn_string);
            //Log::write("debug", "sucesso, retornando...");
            return $endpoint;
        }

        return false;
    }

}