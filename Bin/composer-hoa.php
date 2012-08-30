<?php
namespace {

    function _define($name, $value, $case = false)
    {

        if (!defined($name))
            return define($name, $value, $case);

        return false;
    }

    function cin($out = null)
    {

        if (null !== $out)
            cout($out);

        return trim(fgets(STDIN));
    }

    function cinq($out = null)
    {

        $in = strtolower(cin($out));

        switch ($in) {

            case 'y':
            case 'ye':
            case 'yes':
            case 'yeah': // hihi
                return true;
                break;

            default:
                return false;
        }
    }

    function cout($out)
    {
        $nb = strlen($out);
        if ($out[$nb - 1] !== "\n") {
            $out .= "\n";
        }

        return fwrite(STDOUT, $out);
    }

    function check($out, $test, $die = true)
    {

        if (false === $test) {

            cout('?  ' . $out);

            if (true === $die)
                exit;
            else
                return;
        }

        cout('?  ' . $out);

        return;
    }



    _define('STDIN', fopen('php://stdin', 'rb'));
    _define('STDOUT', fopen('php://stdout', 'wb'));
    _define('STDERR', fopen('php://stderr', 'wb'));
    _define('DS', DIRECTORY_SEPARATOR);

    require_once('Central/Hoa/Core/Core.php');

    from('Hoathis')->import('Scanner');
    from('Hoathis')->import('Composer');

    $scanner = new \Hoathis\Scanner('C:/Users/Julien/Desktop/Central/Hoa/');
    $scanner->scan();

    $composer = new \Hoathis\Composer($scanner);
    $composer->setInteractive(true);
    $composer->generate();


}