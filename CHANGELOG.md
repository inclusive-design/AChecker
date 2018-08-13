
# Changelog

All notable changes to this project will be documented in this file.

## 1.5 - (2018)
### Added
- [ACHECKER-15](https://issues.fluidproject.org/browse/ACHECKER-15) Changelog
- sql upgrade script `achecker_upgrade_1.4_to_1.5.sql`
- Plates template engine

### Changed

- [ACHECKER-4](https://issues.fluidproject.org/browse/ACHECKER-4) 
    The `mysql` syntax were changed to `mysqli`.
- [ACHECKER-3](https://issues.fluidproject.org/browse/ACHECKER-3)
Fixing Issue with last_updated syntax in the `achecker_schema.sql` and `achecker_upgrade_1.4_to_1.5.sql`
- [ACHECKER-3](https://issues.fluidproject.org/browse/ACHECKER-3)
Changing all "0000-00-00 00:00:00" values to NULL, modifying `date` to `datetime` and also changing from `version 1.4` to `version 1.5`
- [ACHECKER-5](https://issues.fluidproject.org/browse/ACHECKER-5)
Change static calls to method that are not static 
- [ACHECKER-5](https://issues.fluidproject.org/browse/ACHECKER-5)
Old style contructors, are replaced with `__construct()`
- [ACHECKER-5](https://issues.fluidproject.org/browse/ACHECKER-5)
Deprecated call-by-references codes
- [ACHECKER-6](https://issues.fluidproject.org/browse/ACHECKER-6) Template Engine: Savant2 to Plate
- [ACHECKER-8](https://issues.fluidproject.org/browse/ACHECKER-8) PCLZIP was changed to ZipArchive Class
- [ACHECKER-9](https://issues.fluidproject.org/browse/ACHECKER-9) Upgrade securimage library from version 1.0.2 to version 3.6.7

### Removed
- Savant2 Template Engine
- PCLZIP Library
- Older versions of securimage and simple html dom parser libraries
