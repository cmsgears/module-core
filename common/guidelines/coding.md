Below mentioned coding convention can be used to follow CMG. Though it's not mandatory or necessary to follow it, but we highly recomment it for CMG module, plugin, widget and themes development.

Generic:
========================================
* Follow camelcase naming convention to name a class and it's methods and variables.

Fillers:
========================================
* Add one filler line between the imports from different modules.
* Add one filler line before starting a new block and before first statement of each block. The block could be class, method, if, if else, while, do while.
* We can exclude filler line before first statement of closure(anonymous method).

Class:
========================================
* Namespace must be the first line.
* All the use statements must be used before starting the class.
* One file, one class rule to identify class using file names. The file name and class name must be same.
* Add one line as filler between different type of imports and from different modules.
* Though it's not mandatory, but it will be nice to include Yii imports followed by CMG specific imports before project imports.

Methods:
========================================
* We can follow the class hierachy while overriding methods.

Controllers:
========================================
* We have declared modelService as public due to it's anonymous nature depending on the controller.
* The metaService also need public access modifier similar to modelService.
* Rest of the services are declared as protected since these are available outside controller by their global name.