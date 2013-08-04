$CL.require('Cl.Qunit.Qunit');

QUnit.module("Application.Service.UserManagerTest");

QUnit.test("True or false", function(assert) {
    assert.equal(true, false, "false should be true");
});