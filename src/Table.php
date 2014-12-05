<?php

namespace Adagio\Table;

class Table
{
    /**
     *
     * @var array
     */
    protected $data;

    /**
     *
     * @param array $data
     */
    public function __construct($data = [])
    {
        $this->data = $data;
    }

    /**
     *
     * @param array $data
     *
     * @return string
     */
    public function htmlize($data = null)
    {
        $data = is_null($data) ? $this->data : $data;

        $columns = array();

        // Collect headers
        foreach ($data as $row) {
            foreach ($row as $key => $value) {
                $columns[$key] = $key;
            }
        }

        $sets = array_combine($columns, array_fill(1, count($columns), array()));

        $body = '';
        $n = 1;
        foreach ($data as $row) {
            $body .= "<tr><td>$n</td>";
            foreach ($columns as $key) {
                if (array_key_exists($key, $row)) {
                    $body .= '<td>'.($v = $this->show($row[$key])).'</td>';
                    // Collect value set for limited columns
                    $sets[$key][$v] = $v;
                } else {
                    $body .= '<td></td>';
                }
            }
            $body .= "</tr>\n";
            $n++;
        }

        $headers = '';
        foreach ($columns as $key) {
            $note = '';
            if (count($sets[$key]) <= 5) {
                $note = ' title="Values: '.implode(', ', $sets[$key]).'"';
            }
            $headers .= "<th$note>$key</th>";
        }

        return "<table class=\"table table-bordered\"><thead><tr><th>#</th>$headers</tr></thead><tbody>\n$body</tbody></table>\n";
    }

    public function show($value)
    {
        if (is_array($value)) {
            return '['.implode(', ', array_map([$this, 'show'], $value)).']';
        } elseif (is_bool($value)) {
            return $value ? 'true' : 'false';
        } else {
            return $value;
        }
    }

    public function web($content)
    {
        echo "<!doctype html>
    <html>
    <head>
        <meta charset=\"UTF-8\">
        <link href=\"//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css\" rel=\"stylesheet\">
    </head>
    <body>
    $content
    </body>
    </html>";
    }
}
