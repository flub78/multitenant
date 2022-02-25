# Development Introspection

The project has been active for a while and I am starting to ask myself some questions about development.

* How much did the code generation cost me ?
* What percentage of the project resources are spent on refactoring, testing, new features, etc. ?

So I started to consider, using some predefined keywords in commit messages to extract this information.

## possible list of keywords:

    By type
        Bug fix
        DevOps          scripts, installation, jobs
        Tests
        Refactoring
        Architecture
        Feature
        
    By Feature/Resource
        Tenants
        Backups
        Users
        Roles
        Calendar

        Authentication
        Attachments
        Code generator
        REST API
        Ticket #456
        
Not that one or several keywords can be applied to a single commit. 

Unfortunately I did not enforce this policy from the beginning. I will try to be more systematic and regular in future commit messages. 

I am considering fetching the commit log to perform some analysis and get the percentage of commits in every category. Percentage of commits is likely a good enough proxy for the percentage of efforts. I do not want to waste time and efforts on collecting time spent, the extracted information would not be more accurate or more useful.

Note that I'll stay agile and will rely on keywords even if it is not perfectly accurate. It is more flexible than to force commit through a tool to define categories and prevent commits that not following the convention. 

The extracted information will be as good as the commit discipline but the extraction cost and overhead on developers will stay almost null (just taking the habits to insert keywords in commit messages). And I do not care if I spent 13 or 15 % on refactoring but I do care if I spend more that 20 % or less that 1 % on it. Trends are also important to determine if the process is under control.
 
# Results of the first draft

    php artisan dev:stats

Commits = 421
Bug fixes : 9.26 %
DevOps : 2.61 %
Tests : 30.64 %
Refactoring : 14.01 %
Architecture : 3.80 %
Tenants : 8.55 %
Backups : 4.99 %
Users : 7.84 %
Calendar : 15.20 %
Authentication : 1.19 %
Attachements : 0.24 %
Code generation : 21.38 %
REST API : 4.75 %
Localization : 7.13 %
Configuration : 2.61 %
Roles : 4.28 %
not classified = 9.03 %
