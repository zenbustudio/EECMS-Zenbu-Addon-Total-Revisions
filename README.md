# Total Revisions, an example addon for Zenbu for EECMS

> This addon requires [Zenbu](https://zenbustudio.com/software/zenbu) for [ExpressionEngine](https://expressionengine.com) 4 or 5 to be installed on your ExpressionEngine instance.

Displays a column in [Zenbu](https://zenbustudio.com/software/zenbu) with the total revisions for each entry. An example addon for Zenbu to showcase how to add custom columns.

This is done through the use of `zenbu_modify_results` and `zenbu_main_content_end` hooks present in Zenbu. See **ext.zenbu_total_revisions.php** for details.

## Installation

1. Upload the `/system/user/addons/zenbu_total_revisions` folder (with all its contents) to your `/system/user/addons` folder.
2. Log into your ExpressionEngine Control Panel and enable the addon from _Developer => Add-Ons_
3. Visit the Zenbu main page. There should be a column at the end labeled **Total Revisions**.

# License

MIT License

Copyright (c) 2019 Zenbu Studio

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.