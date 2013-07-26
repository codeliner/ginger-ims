var Feature = $CL.namespace('Ginger.Application.Model.Feature');

$CL.require('Ginger.Application.Model.Feature.AbstractFeature');

Feature.FilterFeature = function() {};

Feature.FilterFeature = $CL.extendClass(Feature.FilterFeature, Feature.AbstractFeature);