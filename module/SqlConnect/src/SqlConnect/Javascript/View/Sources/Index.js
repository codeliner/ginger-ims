var Sources = $CL.namespace('SqlConnect.View.Sources');

$CL.require('Cl.Backbone.View');

Sources.Index = function() {};

Sources.Index = $CL.extendClass(Sources.Index, Cl.Backbone.View, {
    sourceCollection : null,
    setSourceCollection : function(sourceCollection) {
        this.sourceCollection = sourceCollection;
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
            this.sourceCollection.setConnection(connectionName);
            $CL.app().wait();
            this.sourceCollection.reset();
            this.sourceCollection.fetch({
                success : $CL.bind(function(col) {
                    col.each($CL.bind(function(source) {
                        var $tr = $('<div />').addClass('ts-tr');
                        var iconClass = 'icon-plus';

                        if (source.get('is_source')) {
                            $tr.addClass('info');
                            iconClass = 'icon-minus';
                        }

                        var $td1 = $('<div />').addClass('ts-td10').html(
                            $('<a />').attr('href', '#' + helpers.uri('sqlconnect_source', {
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
        isSource = false;

        if ($a.find('i').hasClass('icon-plus')) {
            isSource = true;
        }

        var source = this.sourceCollection.get($a.data('id'));

        source.save(
            {is_source : isSource},
            {
                success : function() {
                    $a.find('i').toggleClass('icon-plus icon-minus');
                    $a.parents('.ts-tr').toggleClass('info');
                },
                error : function(model, jqX) {
                    $CL.get("application").alert("Failed saving source.", jqX);
                }
            }
        );
    }
});