// const { forEach } = require("lodash");

$(document).ready(function() {
    new_node = [];
    rename_node = [];
    move_parent = [];
    gtree = $("#chartofaccount").jstree(true);
    //  = tree;

    function getFolderContextMenu(node) {

        var urlCreate = $('#create-level').data('create');
        var urlEdit = $('#edit-level').data('edit');

        // Get the DOM element of the node
        var nodeElement = $("#" + node.id);

        // Check if the node has the "jstree-last" class (Unallocated) and return no context menu options
        
        const firstLine = nodeElement[0].innerText.trim().split('\n')[0].trim();

        if (node.parent === "#" && firstLine.endsWith('Unallocated')) {
            return false; // No context menu for root "Unallocated" node
        }
        
        // Check if it's the main head (root node)
        if (node.parent === "#") {
            return {
                "Create": {
                    "separator_before": false,
                    "separator_after": true,
                    "label": "Create Level",
                    "action": function(obj) {
                        var parentId = node.id;
                        var urlCreateLevel = urlCreate.replace('?', parentId);
                        window.location.href = urlCreateLevel;
                    }
                }
            };
        } else {
            return {
                "Create": {
                    "separator_before": false,
                    "separator_after": true,
                    "label": "Create Level",
                "action": false,
                    action: function(obj) {
                    var parent = node;
                    var id;
                    var child = { id: null, text: "New File", type: 'folder' };
                    
                        var parentId = node.id;
                        var urlCreateLevel = urlCreate.replace('?', parentId);
                        window.location.href = urlCreateLevel;
                    },
                },

                "Edit Level": {
                    "separator_before": false,
                    "separator_after": false,
                    "label": "Edit Level",
                    "action": function(obj) {
                        var urlEditLevel = urlEdit.replace('?', node.id);
                        window.location.href = urlEditLevel;
                    }
                },
            };
        }
    }

    var data = $('#treeData').data('tree');
    $('#chartofaccount').jstree({
        'core': {
            'data': data,
            'check_callback': true,
            'themes': {
                'responsive': true
            },
            'multiple': true,
            'animation': true,
            'search': {
                'case_sensitive': false,
                'show_only_matches': true
            }
        },
        'contextmenu': {
            "items": function(node) {
                var tree = $("#chartofaccount").jstree(true);
                if (node.original.type == 'file')
                    return false;
                    return getFolderContextMenu(node, tree);
            }
        },
        'dnd':{
            "is_draggable": function (node, e) {
                gtree = $("#chartofaccount").jstree(true);
                move_node  = gtree.get_node(e.target);
                if(move_node.original.parent == '#'){ //cannot drag and drop root folders
                    e.preventDefault();
                    return false;
                }
                 // return false;  // flip switch here.
                return true;
            }
        },
        'plugins': plugins,
        'types': {
            folder: {
                icon: 'far fa-folder'
            },
            level0: {
                icon: 'far fa-folder',
            },
            level1: {
                icon: 'far fa-folder',
            },
            level2: {
                icon: 'far fa-folder',
            },
            level3: {
                icon: 'far fa-folder',
            },
            file: {
                icon: 'far fa-file',
                valid_children: 'none',
                a_attr: 'hello.html'
            }
        }
        })
        //push new child to new_node array we will be creating childs manually
    $('#chartofaccount').on('create_node.jstree', function(e, data) {
        new_node.push({
            parent: data.parent,
            node: { level: data.node.text, type: 'folder' },
            js_id: data.node.id
        })
    });

    //move_node.jstree
    $('#chartofaccount').on('move_node.jstree', function(e, data) {
        //if folder is moved
        if (data.node.type == "folder") {
            //check if moved folder at his previous position
            objIndex = move_parent.findIndex((obj => obj.id == data.node.id && obj.previous == data.parent));
            if (objIndex == -1) {
                //store data if folder is moved
                move_parent.push({
                    id: data.node.id,
                    current: data.parent,
                    previous: data.old_parent
                });
            } else {
                //delete data from array if moved folder at his previous position
                move_parent = move_parent.filter(function(value, index, arr) {
                    return value.id != data.node.id;
                });
            }

        }
        objIndex = new_node.findIndex((obj => obj.js_id == data.node.id));
        if (objIndex != -1)
            new_node[objIndex].parent = data.parent;
        // console.log(move_parent)
    });

    // rename_node.jstree
    $('#chartofaccount').on('rename_node.jstree', function(e, data) {
        //check if new node is renamed or existing node
        if (new_node.length === 0) {
            //if true existing node is renamed
            //check if old text and new text is same
            if (data.text !== data.old) {
                //restrict user to change default nodes
                if (data.old === "Chart Of Accounts" || data.old === "Unallocated")
                    return
                    //renaming was actually done
                rename_node.push({
                    id: data.node.id,
                    text: data.text,
                    old: data.old
                })
            }
        } else {
            objIndex = new_node.findIndex((obj => obj.js_id == data.node.id));
            console.log(objIndex);
            if (objIndex != -1)
                new_node[objIndex].node.level = data.text;
        }

    });

    // Chart of account searching
    $('#chartofaccount_search').on('input', function (e) {
        var searchString = e.target.value;
        $('#chartofaccount').jstree(true).search(searchString);
    });

});
