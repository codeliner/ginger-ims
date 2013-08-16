var ModuleElement = $CL.namespace('Ginger.Application.Service.ModuleElement');

ModuleElement.ElementInterface = function() {};

ModuleElement.ElementInterface.prototype = {
    /**
     * Return a view object, which renders an option form for element specific options
     *
     * @param {object} elementData Object with elementData (id,name,link,module) and previous set options or empty options if a new task is set
     *
     * @return {Cl.Backbone.View} | null
     */
    getOptionsView : function(elementData) {},
    /**
     * Get options out of form and return them as object
     *
     * If server validation is required, you should do it as SJAX call
     * If validation fails, return a boolean false
     *
     * @return {object} | {boolean}
     */
    collectOptions : function() {},

    /**
     * Return a view which renders some information about the element or give some handling hints
     *
     * If no help informations are available, simply return null
     *
     * @return {Cl.Backbone.View} | null
     */
    getHelpView : function(elementData) {}
};