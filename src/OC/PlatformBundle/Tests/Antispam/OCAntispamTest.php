<?php
/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 09/02/2016
 * Time: 21:27
 */

namespace OC\PlatformBundle\Tests\Antispam;

use OC\PlatformBundle\Antispam\OCAntispam;

class OCAntispamTest extends \PHPUnit_Framework_TestCase
{

    public function testIsSpam()
    {
        //init
       // $swiftMailer = $this->getMock(\Swift_Mailer::class);

        $swiftMailer = $this->getMockBuilder('\Swift_Mailer')
            ->disableOriginalConstructor()
            ->getMock();

        $antiSpam = new OCAntispam( $swiftMailer ,3);

        //run
        $expect = true;
        $result = $antiSpam->isSpam("to");

        $expect2 = false;
        $result2 = $antiSpam->isSpam("toto");

        //assert
        $this->assertEquals($expect, $result);
        $this->assertEquals($expect2, $result2);


    }

}