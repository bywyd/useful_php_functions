<?php
class ClientIPMatch
{
    private $ips;

    public function __construct(array $authorized_ips)
    {
        $this->ips = $authorized_ips;
    }

    public function client_ip()
    {
        if (!empty($_SERVER["HTTP_CLIENT_IP"]))
        {
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        }
        elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
        {
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        }
        else
        {
            $ip = $_SERVER["REMOTE_ADDR"];
        }

        return $ip;
    }

    public function IsIPAuthorized()
    {
        $auth = False;
        foreach ($this->ips as $key => $value) {
            if ($value == $this->client_ip())
            {
                $auth = True;
            }
        }

        return $auth;
    }
}
