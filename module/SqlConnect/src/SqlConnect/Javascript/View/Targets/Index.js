var Targets = $CL.namespace('SqlConnect.View.Targets');

$CL.require('Cl.Backbone.View');

Targets.Index = function() {};

Targets.Index = $CL.extendClass(Targets.Index, Cl.Backbone.View, {
    targetCollection : null,
    setTargetCollection : function(targetCollection) {
        this.targetCollection = targetCollection;
    },
    events : {
        'change select[name=connection]' : 'onConnectionChange',
        'click .js-sources .js-change-source' : 'onChangeSourceClick'
    },
    render : function() {
        this.parent.prototype.render.apply(this);

        if (this.data.connection) {
            this.$el.find('select[name=connection]').val(this.data.connection).change();
        }
    },
    onConnectionChange : function(e) {
        this.$el.find('.js-sources .ts-tr').remove();

        var connectionName = $(e.target).val();

        if (connectionName != 'none') {
            this.targetCollection.setConnection(connectionName);
            $CL.app().wait();
            this.targetCollection.reset();
            this.targetCollection.fetch({
                success : $CL.bind(function(col) {
                    col.each($CL.bind(function(source) {
                        var $tr = $('<div />').addClass('ts-tr');
                        var iconClass = 'icon-plus';

                        if (source.get('is_target')) {
                            $tr.addClass('info');
                            iconClass = 'icon-minus';
                        }

                        var $td1 = $('<div />').addClass('ts-td10').html(
                            $('<a />').attr('href', '#' + helpers.uri('sqlconnect_target', {
                                connection : connectionName,
                                action : 'show',
                                id : source.get('id')
                            })).html(source.get('name'))
                        );

                        $tr.append($td1);
                        var $td2 = $('<div />').addClass('ts-td2').html(
                            $('<a />').attr('href', '#').addClass('js-change-source')
                            .data('id', source.get('id'))
                            .html($('<i />').addClass(iconClass))
                        );
                        $tr.append($td2);
                        this.$el.find('.js-sources').append($tr);
                    }, this));

                    $CL.app().stopWait();
                }, this),
                error : function(col, jqX) {
                    $CL.app().stopWait().alert("Failed fetching sources.", jqX);
                }
            });
        }
    },
    onChangeSourceClick : function(e) {
        e.preventDefault();

        var $a = $CL.jTarget(e.target, 'a'),
        isTarget = false;

        if ($a.find('i').hasClass('icon-plus')) {
            isTarget = true;
        }

        var source = this.targetCollection.get($a.data('id'));

        source.save(
            {is_target : isTarget},
            {
                success : function() {
                    $a.find('i').toggleClass('icon-plus icon-minus');
                    $a.parents('.ts-tr').toggleClass('info');
                },
                error : function(model, jqX) {
                    $CL.get("application").alert("Failed saving target.", jqX);
                }
            }
        );
    }
});