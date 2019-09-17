# ACF Component

A component based helper for Advanced Custom Fields that groups fields into Components that can be added to Templates.

## Requires
- Themosis 2.0+
- Advanced Custom Fields 5.8+

## Issues

You will have to move the route instantiation from a Service Provider to a Hook. Currently the Templates/Components have to be loaded before the routes have loaded but after plugins have loaded.  
By default in themosis routes are loaded before plugins have loaded. I moved it into the default Application hook provided by Themosis and disabled the RouteProvider