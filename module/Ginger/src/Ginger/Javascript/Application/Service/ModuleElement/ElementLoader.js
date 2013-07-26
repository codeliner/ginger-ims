var ModuleElement = $CL.namespace('Ginger.Application.Service.ModuleElement');

$CL.require("Ginger.Application.Service.ModuleElement.ElementInterface");

ModuleElement.ElementLoader = function(){};

ModuleElement.ElementLoader.prototype = {
    /**
     * Load a module element by module and class
     *
     * Element is loaded via ServiceManager, so you can use a factory to setup element
     *
     * @param {object}   elementData Simple object with .class and .module attributes
     * @param {function} callback    Is called after element is loaded, callback gets the element as param
     *
     * @return void
     */
    loadElement : function(elementData, callback) {
        $CL.app().lazyLoadModule(elementData.module, function() {
            var element = $CL.get(elementData['class'].replace(/\\/g, '.'), elementData);

            if (!$CL.isInstanceOf(element, Ginger.Application.Service.ModuleElement.ElementInterface)) {
                $CL.exception(
                    "Module Element has to be instance of Ginger.Application.Service.ModuleElement.ElementInterface",
                    $CL.className(this),
                    {element : element}
                );
            }
            callback(element);
        });
    }
};