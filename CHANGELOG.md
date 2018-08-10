
# Changelog

All notable changes to this project will be documented in this file.

## 1.5 - (2018)
### Added
- Changelog
- sql upgrade script `achecker_upgrade_1.4_to_1.5.sql`
- Plates template engine

### Changed

- [#84](https://github.com/inclusive-design/AChecker/pull/84) 
    The `mysql` syntax were changed to `mysqli`.
- [#86](https://github.com/inclusive-design/AChecker/pull/86)
Fixing Issue with last_updated syntax in the `achecker_schema.sql` and `achecker_upgrade_1.4_to_1.5.sql`
- [#81](https://github.com/inclusive-design/AChecker/pull/81), [#83](https://github.com/inclusive-design/AChecker/pull/83), and [#86](https://github.com/inclusive-design/AChecker/pull/86)
Changing all "0000-00-00 00:00:00" values to NULL, modifying `date` to `datetime` and also changing from `version 1.4` to `version 1.5`
- [#85](https://github.com/inclusive-design/AChecker/pull/85/files#diff-a80b6a376616d67306ba8f0cfad27bb9R31)
Change static calls to method that are not static 
- [#85](https://github.com/inclusive-design/AChecker/pull/85/files#diff-a80b6a376616d67306ba8f0cfad27bb9R31)
Old style contructors, are replaced with `__construct()`
- [#85](https://github.com/inclusive-design/AChecker/pull/85), [#91](https://github.com/inclusive-design/AChecker/pull/91) and [#93](https://github.com/inclusive-design/AChecker/pull/93)
Deprecated call-by-references codes
- [#92](https://github.com/inclusive-design/AChecker/pull/92) Template Engine: Savant2 to Plate
- [#88](https://github.com/inclusive-design/AChecker/pull/88) PCLZIP was changed to ZipArchive Class
- [#89](https://github.com/inclusive-design/AChecker/pull/89) Upgrade securimage library from version 1.0.2 to version 3.6.7

- [#95](https://github.com/inclusive-design/AChecker/pull/95) Changing simple html dom parser from version 0.98 to version 1.5



### Removed
- Savant2 Template Engine
- PCLZIP Library
- Older versions of securimage and simple html dom parser libraries
