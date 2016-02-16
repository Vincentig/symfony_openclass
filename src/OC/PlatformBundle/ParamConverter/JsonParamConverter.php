<?php
/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 15/02/2016
 * Time: 11:51
 */

namespace OC\PlatformBundle\ParamConverter;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

class JsonParamConverter implements ParamConverterInterface
{

    /**
     * Stores the object in the request.
     *
     * @param Request $request The request
     * @param ParamConverter $configuration Contains the name, class and options of the object
     *
     * @return bool True if the object has been successfully set, else false
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        // On récupère la valeur actuelle de l'attribut
        $json = $request->attributes->get('json');

        // On effectue notre action : le décoder
        $json = json_decode($json, true);

        // On met à jour la nouvelle valeur de l'attribut
        $request->attributes->set('json', $json);
    }

    /**
     * Checks if the object is supported.
     *
     * @param ParamConverter $configuration Should be an instance of ParamConverter
     *
     * @return bool True if the object is supported, else false
     */
    public function supports(ParamConverter $configuration)
    {
        // Si le nom de l'argument du contrôleur n'est pas "json", on n'applique pas le convertisseur
        if ('json' !== $configuration->getName()) {
            return false;
        }

        return true;
    }
}